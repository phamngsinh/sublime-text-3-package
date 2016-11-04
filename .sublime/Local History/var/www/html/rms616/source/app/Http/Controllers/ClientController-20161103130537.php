<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Country;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;
use Symfony\Component\Console\Helper\Helper;
use Yajra\Datatables\Datatables;
use App\Http\Requests;
use PDF;

class ClientController extends Controller
{
    var $defaultYes;

    public function __construct()
    {
        $this->middleware('auth');
        $this->defaultYes = config('frontend.client.defaultYes');
        $this->defaultNo = config('frontend.client.defaultNo');
        $this->defaultCountry = Country::create();
        $this->defaultCountry->long_name = config('frontend.defaultTextForNull');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //Get All Params
            $filter = $request->all();

            //if have filter
            if (isset($filter['filterData'])) {

                //Decode all request params
                $clientFilters = json_decode($filter['filterData']);

                //Get all countries in cache
                $listCountries = Cache::remember('countries', 90, function () {
                    return Country::get();
                });

                //Get clients with relation
                $clients = Client::with([
                    'activity',
                    'incorporatedCountry',
                    'supplierCountry',
                    'customerCountry',
                    'permanentResidency'
                ]);

                $clients = self::filterClients($clients, $clientFilters, $listCountries);

            } else {
                //Get all client with relation
                $clients = Client::with(['activity', 'incorporatedCountry', 'supplierCountry', 'customerCountry', 'permanentResidency']);

            }

            //Return data table
            return self::processClientDataTable($clients);
        } else {

            //Return view in admin/user/index
            return View('client.index');
        }

    }

    /**
     *
     */
    public function exportPDF(Request $request)
    {

        //Get All Params
        $filter = $request->all();

        //if have filter
        if (isset($filter['filterData'])) {

            //Decode all request params
            $clientFilters = json_decode($filter['filterData']);

            //Get all countries in cache
            $listCountries = Cache::remember('countries', 90, function () {
                return Country::get();
            });

            //Get clients with relation
            $clients = Client::with([
                'activity',
                'incorporatedCountry',
                'supplierCountry',
                'customerCountry',
                'permanentResidency'
            ]);

            $clients = self::filterClients($clients, $clientFilters, $listCountries);

        }else{
            //Get all client with relation
            $clients = Client::with(['activity', 'incorporatedCountry', 'supplierCountry', 'customerCountry', 'permanentResidency']);
        }

        //Call template with users
        $pdf = PDF::loadView('pdf.clients', ['clients' => $clients->get()]);

        //Set pdf document properties and download
        return $pdf->setPaper('a3', 'landscape')->download('clients.pdf');
    }

    /**
     * @param $clients
     * @param $filterParams
     * @param $countries
     */
    private function filterClients($clients, $filterParams, $countries)
    {
        //Filter with incorporated_country
        if (isset($filterParams->incorporated_country) && !empty($filterParams->incorporated_country) && is_array($filterParams->incorporated_country)) {

            //Parse in all values of param.
            $countryFilter = parseIntValuesOfArray($filterParams->incorporated_country);

            //Get countries filtered
            $countriesFiltered = self::getCountriesByTatf($countries, $countryFilter);

            $clients->whereIn('incorporated_country', $countriesFiltered);
        }

        //Filter with supplier_country
        if (isset($filterParams->supplier_country) && !empty($filterParams->supplier_country) && is_array($filterParams->supplier_country)) {
            $countryFilter = parseIntValuesOfArray($filterParams->supplier_country);

            $countriesFiltered = self::getCountriesByTatf($countries, $countryFilter);

            $clients->whereIn('supplier_country', $countriesFiltered);
        }

        //Filter with customer_country
        if (isset($filterParams->customer_country) && !empty($filterParams->customer_country) && is_array($filterParams->customer_country)) {
            $countryFilter = parseIntValuesOfArray($filterParams->customer_country);

            $countriesFiltered = self::getCountriesByTatf($countries, $countryFilter);

            $clients->whereIn('customer_country', $countriesFiltered);
        }

        //Filter with permanent_country
        if (isset($filterParams->permanent_country) && !empty($filterParams->permanent_country) && is_array($filterParams->permanent_country)) {
            $countryFilter = parseIntValuesOfArray($filterParams->permanent_country);

            $countriesFiltered = self::getCountriesByTatf($countries, $countryFilter);

            $clients->whereIn('permanent_residency', $countriesFiltered);
        }

        //filter with risk
        if (isset($filterParams->risk) && !empty($filterParams->risk) && is_array($filterParams->risk)) {
            $clients->whereIn('risk', $filterParams->risk);
        }

        return $clients;
    }

    /**
     * @param $countries
     * @param array $filter
     * @return mixed
     */
    private function getCountriesByTatf($countries, array $filter)
    {
        return $countries->whereIn('fatf', $filter)
            ->lists('id')
            ->toArray();
    }

    /**
     * Create a data tables
     * @param $clients
     * @return mixed
     */
    private function processClientDataTable($clients)
    {
        $nullTextDefault = config('frontend.defaultTextForNull');

        return Datatables::of($clients)
            //Return type
            ->editColumn('risk', function ($clients) {
                return self::filterRiskInArray($clients);
            })

            //Show Client code
            ->editColumn('client_code', function ($clients) use ($nullTextDefault) {
                return ($clients->client_code) ? str_limit($clients->client_code, 10) : $nullTextDefault;
            })

            //Show cash_involved
            ->editColumn('cash_involved', function ($clients) {
                return ($clients->cash_involved == ($this->defaultYes['value'])) ? $this->defaultYes['name'] : $this->defaultNo['name'];
            })

            //show met_personally
            ->editColumn('met_personally', function ($clients) {
                return ($clients->met_personally == ($this->defaultYes['value'])) ? $this->defaultYes['name'] : $this->defaultNo['name'];
            })

            ->editColumn('yearly_turnover', function ($clients) {
                return number_format((float)$clients->yearly_turnover, 2, '.', ',');
            })

            //show politically_exposed
            ->editColumn('politically_exposed', function ($clients) {
                return ($clients->politically_exposed == ($this->defaultYes['value'])) ? $this->defaultYes['name'] : $this->defaultNo['name'];
            })

            //show trust_service_provider_offered
            ->editColumn('trust_service_provider_offered', function ($clients) {
                return ($clients->trust_service_provider_offered == ($this->defaultYes['value'])) ? $this->defaultYes['name'] : $this->defaultNo['name'];
            })

            //show activity
            ->editColumn('activity', function ($clients) use ($nullTextDefault){
                return (isset($clients->activity) && !is_null($clients->activity->name)) ? str_limit($clients->activity->name, 15) : $nullTextDefault;
            })

            //Customize status column. Return custom html with is_active
            ->editColumn('is_active', function ($clients) {

                $html = ($clients->is_active === config('administrator.activity.activeStatus')) ?
                    '<span class="label label-success">Active</span>' :
                    '<span class="label label-danger">Deactive</span>';

                return $html;
            })

            //Customize action column. Add edit button to table
            ->addColumn('actions', function ($clients) {

                $html = '<a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                         <a href="#" data-toggle="modal" data-target="#delete-client"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';

                return $html;
            })
            ->make(true);
    }

    /**
     * @param $clients
     * @return string
     */
    public static function filterRiskInArray($clients){

        //Search current activity type from array
        $collection = collect(config('frontend.client.riskLevel'))
            ->filter(function ($value, $key) use ($clients) {
                return $key == $clients->risk;
            })
            ->first();

        return ($collection) ? str_limit($collection, 50) : config('frontend.defaultTextForNull');
    }
}