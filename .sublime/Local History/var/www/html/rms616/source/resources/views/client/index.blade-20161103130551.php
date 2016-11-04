@extends('layouts.app')
@section('page_styles')
    <script>
        var paramDataTable = {};
        var urlClients = "{{route('clients.index')}}";
        var urlExportPDF = "{{route('clientsPDF')}}";
    </script>
    <!-- DataTables CSS -->
    <link href="css/sb-admin/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="css/sb-admin/dataTables.responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/main.css">
@endsection
@section('content')
    <div id="wrap">
        <div class="main">

            <div class="container">
                <div class="header-client">
                    <div class="header-client hidden-xs">
                        <div class="row">
                            <div class="col-sm-10">
                                <h2 class="text-puple text-uppercase inline-block"> client list </h2>
                                <a style="width: 200px; vertical-align: super;" href="#" class="btn-tw-puple inline-block text-center">Upgrade Subscription</a>
                            </div>
                            <div class="col-sm-2  margin-top-20">
                                <a style=" vertical-align: super;" class="btn-export btn-tw-puple inline-block text-center">Export PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="header-client visible-xs">
                        <h2 class="text-puple text-uppercase inline-block"> client list </h2>
                        <div class="row">
                            <div class="col-xs-7   margin-top-20">
                                <a style="vertical-align: super;" href="#" class="btn-tw-puple inline-block text-center">Upgrade Subscription</a>
                            </div>
                            <div class="col-xs-5  margin-top-20">
                                <a style=" vertical-align: super;" class="btn-export btn-tw-puple inline-block text-center">Export PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-client margin-top-20">
                    <div id="wrapper">
                        <div class="block-header-list">
                            <p class="text-blue-l inline-block size-16">You have used <span class="text-red">100</span> / 250 clients available</p>
                            <a style="width:120px; vertical-align: super;" href="{{route('risks.index')}}" class="btn-tw-puple pull-right inline-block text-center"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp; Add client</a>
                        </div>
                        <table width="100%" class="table stripe row-border order-column" id="dataTables-clients">
                            <thead>
                            <tr>
                                <th><strong>NO</strong></th>
                                <th>CLIENT</th>
                                <th>
                                    <button href="" class="option-select level-risk">RISK LEVEL<span class="caret"></span></button>
                                </th>
                                <th>
                                    ACTIVITIES
                                </th>
                                <th>YEARLY <br> TURNOVER</th>
                                <th>
                                    <button data-id='incorporated' class="option-select level-country">INCORPORATED <br>  COUNTRY<span class="caret"></span></button>
                                </th>
                                <th>
                                    <button data-id='supplier' class="option-select level-country">SUPPLIER <br> COUNTRY<span class="caret"></span></button>
                                </th>
                                <th>
                                    <button data-id='customer' class="option-select level-country">CUSTOMER <br> COUNTRY<span class="caret"></span></button>
                                </th>
                                <th>CASH <br> INVOLVED</th>
                                <th>MET <br> PERSONALLY</th>
                                <th>POLITICALLY <br>EXPOSED</th>
                                <th>
                                    TRUST <br> SERVICE <br> PROVIDERS <br> OFFERED
                                </th>
                                <th>
                                    <button data-id='residentcy' class="option-select level-country">PERMANENT  <br> RESIDENCY<span class="caret"></span></button>
                                </th>
                                <th>ACTION</th>
                            </tr>
                            </thead>

                        </table>
                    </div>

                    <div id="level-risk" class="">
                        <label class="control control--radio">High
                            <input class="client-risk" type="checkbox" name="level-risk[]" value="{{\App\Models\Question::RISK_HIGH_LEVEL}}">
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Normal
                            <input class="client-risk" type="checkbox" name="level-risk[]" value="{{\App\Models\Question::RISK_NORMAL_LEVEL}}">
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Medium
                            <input class="client-risk" type="checkbox" name="level-risk[]" value="{{\App\Models\Question::RISK_MEDIUM_LEVEL}}">
                            <div class="control__indicator"></div>
                        </label>
                    </div>


                    <div id="level-incorporated" class="lvl-country incorporated">
                        <label class="control control--radio">High Risk Countries
                            <input class="level-incorporated-country" type="checkbox" name="level-incorporated[]" value="{{\App\Models\Country::FATF}}">
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Normal Risk Countries
                            <input class="level-incorporated-country" type="checkbox" name="level-incorporated[]" value="{{\App\Models\Country::NOFATF}}">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div id="level-supplier" class="lvl-country supplier">
                        <label class="control control--radio">High Risk Countriess
                            <input class="level-supplier-country" type="checkbox" name="level-country[]" value="{{\App\Models\Country::FATF}}">
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Normal Risk Countries
                            <input class="level-supplier-country" type="checkbox" name="level-country[]" value="{{\App\Models\Country::NOFATF}}">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div id="level-customer" class="lvl-country customer">
                        <label class="control control--radio">High Risk Countries
                            <input class="level-customer-country" type="checkbox" name="level-country[]" value="{{\App\Models\Country::FATF}}">
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Normal Risk Countries
                            <input class="level-customer-country" type="checkbox" name="level-country[]" value="{{\App\Models\Country::NOFATF}}">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div id="level-residentcy" class="lvl-country residentcy">
                        <label class="control control--radio">High Risk Countries
                            <input class="level-permanent-country" type="checkbox" name="level-country[]" value="{{\App\Models\Country::FATF}}">
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio">Normal Risk Countries
                            <input class="level-permanent-country" type="checkbox" name="level-country[]" value="{{\App\Models\Country::NOFATF}}">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div><!-- end main-->
    </div>
@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="js/sb-admin/jquery.dataTables.min.js"></script>
    <script src="js/sb-admin/dataTables.fixedColumns.min.js"></script>
    <script src="js/sb-admin/dataTables.bootstrap.js"></script>
    <script src="js/sb-admin/dataTables.responsive.js"></script>

    <script src="{!! asset('assets/scripts/helper.js') !!}"></script>
    <script src="{!! asset('assets/scripts/pages/page-client.js') !!}"></script>
@endsection
@stop