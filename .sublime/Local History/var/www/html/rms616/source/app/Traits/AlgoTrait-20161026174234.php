<?php
namespace App\Traits;

/**
 * Class AlgoTrait
 * @package App\Traits
 */
use App\Models\Activity;
use App\Models\Country;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

/**
 * Class AlgoTrait
 * @package App\Traits
 */
trait AlgoTrait
{
    /**
     * @var
     */
    protected $sumAFiveUpper;
    /**
     * @var
     */
    protected $sumAFive;

    /**
     * @var
     */
    protected $sumAOneTwenty;

    /**
     * @var
     */
    protected $sumBOneTen;


    /**
     * @var
     */
    protected $sumAFiveteenSix;

    /**
     * @var array
     */
    static $fiveteenSix = ['A15', 'A6'];


    /**
     * @var
     */
    protected $sumAFiveteenSeven;


    /**
     * @var
     */
    protected $sumASixteenSeven;
    /**
     * @var
     */
    protected $sumAFiveteenSevenTeen;


    /**
     * @var
     */
    protected $sumASixteenSeventeen;


    /**
     * @var
     */
    protected $sumASixteenSix;


    /**
     * @var
     */
    protected $sumAFourSevenSeventeen;


    /**
     * @var
     */
    protected $sumAFourTwelveSeventeen;


    /**
     * @var
     */
    protected $sumAFour;


    /**
     * @var
     */
    protected $sumAFourSeven;


    /**
     * @var
     */
    protected $sumNineTenElevenEighteen;

    /**
     * @var
     */
    protected $sumFiveteenSixSeven;


    /**
     * @var
     */
    protected $sumFiveteenSixSeventeen;


    /**
     * @var
     */
    protected $sumSixteenSixSeven;


    /**
     * @var
     */
    protected $sumSixteenSeventeenSeven;


    /**
     * @var
     */
    protected $sumSixteenSeventeenSix;


    /**
     * @var
     */
    protected $sumFourSevenSeventeenTwelve;


    /**
     * @var
     */
    protected $sumFourSeventeenSeven;


    /**
     * @var
     */
    protected $sumFATFSeven;


    /**
     * @var
     */
    protected $sumFATFSeventeen;


    /**
     * @var
     */
    protected $sumFATFSix;

    /**
     * @return AlgoTrait
     */
    public function sumAFive($item)
    {
        $sum = (int)$item->result;
        $this->sumAFive = ($sum <= Question::LOWER_THRESHOLD);
        return $this->sumAFive;
    }

    /**
     * @return AlgoTrait
     */
    public function sumAFiveUpper($item)
    {
        $sum = (float)$item->result;
        $this->sumAFiveUpper = ($sum >= Question::UPPER_THRESHOLD);

        return $this->sumAFiveUpper;
    }

    /**
     * @return mixed
     */
    public function sumAOneTwenty($items)
    {
        $questions = $items;
        $questions->shift();

        array_shift(Question::$listA);
        $questionA = Question::$listA;

        $filtered = $questions->filter(function ($value, $key) use ($questionA) {
            return in_array($value->question_code, $questionA) && ($value->question_code != Question::A_FIVE)
            && (!in_array($value->question_code, Question::$listQuestionCountry)) ;
        });

        //sum result
        $total = 0;
        foreach ($filtered as $question) {
            $tmpQuestion = $question->toArray();

            if(in_array($question->question_code,Question::$listActivityCountry)){
                $activity = $this->getRateActivity((int)$question->result);
                $result = $activity ? $activity->rate : 0;
            }else{
                $result = $this->getRateQuestionOption((int)$question->result, $tmpQuestion['question_options']);
            }

            $total += $result;

        }

        Log::info('A1-A20 = '.$total);
        $this->sumAOneTwenty = $total;
        return $this->sumAOneTwenty;
    }

    /**
     * @Todo check at get relation
     * @param $index
     * @param $questionOption
     * @return mixed
     */
    public function getRateQuestionOption($index, $questionOption)
    {

        foreach ($questionOption as $q) {
            if ($index == $q['id']) {
                return $q['rate'];
            }
        }

        return 0;
    }

    /**
     * @return mixed
     */
    public function sumBOneTen($items)
    {

        $filtered = $items->filter(function ($value, $key) {
            return in_array($value->question_code, Question::$listB);
        });
        //sum result
        $total = 0;
        foreach ($filtered as $question) {
            $tmpQuestion = $question->toArray();
            Log::info('Question B='.$question->question_code);
            Log::info('Result'.$question->result);
            $answerRate = $this->getRateQuestionOption((int)$question->result, $tmpQuestion['question_options']);
            $total += $answerRate * $question->seriousness;

        }

         $this->sumBOneTen = $total;
        return $this->sumBOneTen;
    }

    /**
     * sum  A15 and A6
     * @return mixed
     */
    public function sumAFiveteenSix($items)
    {
        $aFiveTeen = $items->where('question_code', 'A15')->first();

        $aSix = $items->where('question_code', 'A6')->first();

        $aFiveTeenBool = $this->getBooleanOption((int)$aFiveTeen->result, $aFiveTeen->toArray());

        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());

        $this->sumAFiveteenSix = ($aFiveTeenBool == Question::NO) && ($aSixBool == Question::YES);

        return $this->sumAFiveteenSix;
    }

    /**
     * @Todo check at get relation and Const A/B
     * @param $index
     * @param $item
     * @return bool
     */
    public function getBooleanOption($index, $questionOption)
    {
        $items = $questionOption['question_options'];
        foreach ($items as $q) {
            if ($index == $q['id']) {
                return $q['value'];
            }
        }

        return 'No';

    }

    /**
     * sum A15 and A7
     * @return mixed
     */
    public function sumAFiveteenSeven($items)
    {
        $aFiveTeen = $items->where('question_code', 'A15')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aFiveTeenBool = $this->getBooleanOption($aFiveTeen->result, $aFiveTeen->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumAFiveteenSeven = ($aFiveTeenBool == Question::NO) && ($aSevenBool == Question::YES);

        return $this->sumAFiveteenSeven;
    }

    /**
     * A15 A17
     * @return mixed
     */
    public function sumAFiveteenSevenTeen($items)
    {
        $aFiveTeen = $items->where('question_code', 'A15')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aFiveTeenBool = $this->getBooleanOption($aFiveTeen->result, $aFiveTeen->toArray());
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $this->sumAFiveteenSevenTeen = ($aFiveTeenBool == Question::NO) && ($aSevenTeenBool == Question::YES);

        return $this->sumAFiveteenSevenTeen;
    }

    /**
     * A16  A7
     * @return mixed
     */
    public function sumASixteenSeven($items)
    {
        $aSixteen = $items->where('question_code', 'A16')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSixteenBool = $this->getBooleanOption($aSixteen->result, $aSixteen->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumASixteenSeven = ($aSixteenBool == Question::YES) && ($aSevenBool == Question::YES);

        return $this->sumASixteenSeven;
    }

    /**
     * A16  A17
     * @return mixed
     */
    public function sumASixteenSeventeen($items)
    {
        $aSixteen = $items->where('question_code', 'A16')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aSixteenBool = $this->getBooleanOption($aSixteen->result, $aSixteen->toArray());
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $this->sumASixteenSeventeen = ($aSixteenBool == Question::YES) && ($aSevenTeenBool == Question::YES);

        return $this->sumASixteenSeventeen;
    }

    /**
     * A16 A6
     * @return mixed
     */
    public function sumASixteenSix($items)
    {
        $aSixteen = $items->where('question_code', 'A16')->first();
        $aSix = $items->where('question_code', 'A6')->first();
        $aSixteenBool = $this->getBooleanOption($aSixteen->result, $aSixteen->toArray());
        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());
        $this->sumASixteenSix = ($aSixteenBool == Question::YES) && ($aSixBool == Question::YES);

        return $this->sumASixteenSix;
    }

    /**
     * @Todo check list A4
     * A4 = {Car Wash; Consultant; Internet Gabling; Insurance Broker}
     * AND A7 = Yes
     * AND A17 = Yes
     * A4 A7 A17
     * @return mixed
     */
    public function sumAFourSevenSeventeen($items)
    {
        $aFour = $items->where('question_code', 'A4')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSeventeen = $items->where('question_code', 'A17')->first();
        $aFourActivity = $this->getActivity((int)$aFour->result);
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $aSeventeenBool = $this->getBooleanOption($aSeventeen->result, $aSeventeen->toArray());
        $this->sumAFourSevenSeventeen = ($aFourActivity) && ($aSevenBool == Question::YES) && ($aSeventeenBool == Question::YES);

        return $this->sumAFourSevenSeventeen;
    }

    /**
     * @param $index
     * @param $questionOption
     */
    public function getActivity($index)
    {
        return in_array($index,Activity::$firstActivity);


    }
    /**
     * @param $index
     * @param $questionOption
     */
    public function getRateActivity($index)
    {
        if (\Cache::has('activities')) {
            $activities =  \Cache::get('activities');
        }
        \Cache::put('activities', Activity::all(), 10);
        $activities =  \Cache::get('activities');
        return $activities->where('id',$index)->first();
    }

    /**
     * A4 = {Car Wash; Consultant; Internet Gabling; Insurance Broker}
     * AND A12 = Yes
     * AND A17 = Yes
     * @return mixed
     */
    public function sumAFourTwelveSeventeen($items)
    {
        $aFour = $items->where('question_code', 'A4')->first();
        $aTwelve = $items->where('question_code', 'A12')->first();
        $aSeventeen = $items->where('question_code', 'A17')->first();
        $aFourActivity = $this->getActivity((int)$aFour->result);
        $aTwelveBool = $this->getBooleanOption($aTwelve->result, $aTwelve->toArray());
        $aSeventeenBool = $this->getBooleanOption($aSeventeen->result, $aSeventeen->toArray());
        $this->sumAFourTwelveSeventeen = ($aFourActivity) && ($aTwelveBool == Question::YES) && ($aSeventeenBool == Question::YES);

        return $this->sumAFourTwelveSeventeen;
    }

    /**
     * @Todo check list A4
     * A4 = {Military Equipment Agent;
     * Military Equipment Trading; Manufacturing}
     * @return mixed
     */
    public function sumAFour($items)
    {
        $aFour = $items->where('question_code', 'A4')->first();
        $this->sumAFour = in_array((int)$aFour->result,Activity::$secondActivity);
        return $this->sumAFour;
    }

    /**
     * @Todo check list A4
     * A4 = {Casino, nigh club, etc}
     * AND A7 = Yes
     * @return mixed
     */
    public function sumAFourSeven($items)
    {
        $aFour = $items->where('question_code', 'A4')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $aFourActivity = in_array((int)$aFour->result,Activity::$thirdActivity);
        $this->sumAFourSeven = $aFourActivity && ($aSevenBool == Question::YES);

        return $this->sumAFourSeven;
    }

    /**
     * A9 = FATF
     * OR A10 = FATF
     * OR A11 = FATF
     * OR A18 = FATF
     * @return mixed
     */
    public function sumNineTenElevenEighteen($items)
    {
        $aNine = $items->where('question_code', 'A9')->first();
        $aNineCountry = $this->getFATFByQuestion($aNine);
        $aTen = $items->where('question_code', 'A10')->first();
        $aTenCountry = $this->getFATFByQuestion($aTen);
        $aEleven = $items->where('question_code', 'A11')->first();
        $aElevenCountry = $this->getFATFByQuestion($aEleven);
        $aEighteen = $items->where('question_code', 'A18')->first();
        $aEighteenCountry = $this->getFATFByQuestion($aEighteen);
        $this->sumNineTenElevenEighteen = $aNineCountry || $aTenCountry || $aElevenCountry || $aEighteenCountry;

        return $this->sumNineTenElevenEighteen;

    }

    /**
     * @param $item
     * @return mixed
     */
    public function getFATFByQuestion($item)
    {

        $countries = collect();
        if (\Cache::has('countries')) {
            $countries = \Cache::get('countries');
        }
        else {
            $countries = \Cache::rememberForever('countries', function () {
                return Country::all();
            });
        }
        return $countries->where('name', $item->result)->where('fatf', 1)->first();

    }

    /**
     * A15 = No
     * AND A6 = Yes
     * AND A7 = Yes
     * @return mixed
     */
    public function sumFiveteenSixSeven($items)
    {
        $aFiveTeen = $items->where('question_code', 'A15')->first();
        $aSix = $items->where('question_code', 'A6')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aFiveTeenBool = $this->getBooleanOption((int)$aFiveTeen->result, $aFiveTeen->toArray());
        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumFiveteenSixSeven = ($aFiveTeenBool == Question::NO) && ($aSixBool == Question::YES) && ($aSevenBool == Question::YES);

        return $this->sumFiveteenSixSeven;
    }

    /**
     * A15 = No
     * AND A6 = Yes
     * AND A17 = Yes
     * @return mixed
     */
    public function sumFiveteenSixSeventeen($items)
    {
        $aFiveTeen = $items->where('question_code', 'A15')->first();
        $aSix = $items->where('question_code', 'A6')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aFiveTeenBool = $this->getBooleanOption((int)$aFiveTeen->result, $aFiveTeen->toArray());
        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $this->sumFiveteenSixSeventeen = ($aFiveTeenBool == Question::NO) && ($aSixBool == Question::YES) && ($aSevenTeenBool == Question::YES);

        return $this->sumFiveteenSixSeventeen;
    }

    /**
     * A16 = Yes
     * AND A6 = Yes
     * AND A7 = Yes
     * @return mixed
     */
    public function sumSixteenSixSeven($items)
    {
        $aSixTeen = $items->where('question_code', 'A16')->first();
        $aSix = $items->where('question_code', 'A6')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSixTeenBool = $this->getBooleanOption((int)$aSixTeen->result, $aSixTeen->toArray());
        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumSixteenSixSeven = ($aSixTeenBool == Question::YES) && ($aSixBool == Question::YES) && ($aSevenBool == Question::YES);

        return $this->sumSixteenSixSeven;
    }

    /**
     * A16 = Yes
     * AND A17 = Yes
     * AND A7 = Yes
     * @return mixed
     */
    public function sumSixteenSeventeenSeven($items)
    {
        $aSixTeen = $items->where('question_code', 'A16')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSixTeenBool = $this->getBooleanOption((int)$aSixTeen->result, $aSixTeen->toArray());
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumSixteenSeventeenSeven = ($aSixTeenBool == Question::YES) && ($aSevenTeenBool == Question::YES) && ($aSevenBool == Question::YES);

        return $this->sumSixteenSeventeenSeven;
    }

    /**
     * A16 = Yes
     * AND A17 = Yes
     * AND A6 = Yes
     * @return mixed
     */
    public function sumSixteenSeventeenSix($items)
    {
        $aSixTeen = $items->where('question_code', 'A16')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aSix = $items->where('question_code', 'A6')->first();
        $aSixTeenBool = $this->getBooleanOption((int)$aSixTeen->result, $aSixTeen->toArray());
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());
        $this->sumSixteenSeventeenSix = ($aSixTeenBool == Question::YES) && ($aSevenTeenBool == Question::YES) && ($aSixBool == Question::YES);

        return $this->sumSixteenSeventeenSix;
    }

    /**
     * A4 = {Car Wash; Consultant; Internet Gabling; Insurance Broker}
     * AND A7 = Yes
     * AND A17 = Yes
     * AND A12 = Yes
     * @return mixed
     */
    public function sumFourSevenSeventeenTwelve($items)
    {
        $aFour = $items->where('question_code', 'A4')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aTwelve = $items->where('question_code', 'A12')->first();

        $aFourActivity = $this->getActivity((int)$aFour->result);
        $aTwelveBool = $this->getBooleanOption((int)$aTwelve->result, $aTwelve->toArray());
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumFourSevenSeventeenTwelve = ($aFourActivity) && ($aTwelveBool == Question::YES) && ($aSevenTeenBool == Question::YES) && ($aSevenBool == Question::YES);

        return $this->sumFourSevenSeventeenTwelve;
    }

    /**
     * A4 = {Military Equipment Agent; Military Equipment Trading; Manufacturing}
     * AND A17 = Yes
     * A7 = Yes
     * @return mixed
     */
    public function sumFourSeventeenSeven($items)
    {

        $aFour = $items->where('question_code', 'A4')->first();
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSevenTeen = $items->where('question_code', 'A17')->first();

        $aFourActivity = in_array((int)$aFour->result,Activity::$secondActivity);
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $this->sumFourSeventeenSeven = ($aFourActivity) && ($aSevenTeenBool == Question::YES) && ($aSevenBool == Question::YES);

        return $this->sumFourSeventeenSeven;
    }

    /**
     *{A9 OR A10 OR A11 OR A18} = FATF
     * AND A7 = Yes
     */
    public function sumFATFSeven($items)
    {
        $aSeven = $items->where('question_code', 'A7')->first();
        $aSevenBool = $this->getBooleanOption($aSeven->result, $aSeven->toArray());
        $aNine = $items->where('question_code', 'A9')->first();
        $aNineCountry = $this->getFATFByQuestion($aNine);
        $aTen = $items->where('question_code', 'A10')->first();
        $aTenCountry = $this->getFATFByQuestion($aTen);
        $aEleven = $items->where('question_code', 'A11')->first();
        $aElevenCountry = $this->getFATFByQuestion($aEleven);
        $aEighteen = $items->where('question_code', 'A18')->first();
        $aEighteenCountry = $this->getFATFByQuestion($aEighteen);
        $this->sumFATFSeven = ($aSevenBool == Question::YES) && ($aNineCountry || $aTenCountry || $aElevenCountry || $aEighteenCountry);
        return $this->sumFATFSeven;

    }

    /**
     *{A9 OR A10 OR A11 OR A18} = FATF
     * AND A17 = Yes
     */
    public function sumFATFSeventeen($items)
    {
        $aSevenTeen = $items->where('question_code', 'A17')->first();
        $aSevenTeenBool = $this->getBooleanOption($aSevenTeen->result, $aSevenTeen->toArray());
        $aNine = $items->where('question_code', 'A9')->first();
        $aNineCountry = $this->getFATFByQuestion($aNine);
        $aTen = $items->where('question_code', 'A10')->first();
        $aTenCountry = $this->getFATFByQuestion($aTen);
        $aEleven = $items->where('question_code', 'A11')->first();
        $aElevenCountry = $this->getFATFByQuestion($aEleven);
        $aEighteen = $items->where('question_code', 'A18')->first();
        $aEighteenCountry = $this->getFATFByQuestion($aEighteen);
        $this->sumFATFSeventeen = ($aSevenTeenBool == Question::YES) && ($aNineCountry || $aTenCountry || $aElevenCountry || $aEighteenCountry);

        return $this->sumFATFSeventeen;

    }

    /**
     *{A9 OR A10 OR A11 OR A18} = FATF
     * AND A6 = Yes
     */
    public function sumFATFSix($items)
    {
        $aSix = $items->where('question_code', 'A6')->first();
        $aSixBool = $this->getBooleanOption($aSix->result, $aSix->toArray());
        $aNine = $items->where('question_code', 'A9')->first();
        $aNineCountry = $this->getFATFByQuestion($aNine);
        $aTen = $items->where('question_code', 'A10')->first();
        $aTenCountry = $this->getFATFByQuestion($aTen);
        $aEleven = $items->where('question_code', 'A11')->first();
        $aElevenCountry = $this->getFATFByQuestion($aEleven);
        $aEighteen = $items->where('question_code', 'A18')->first();
        $aEighteenCountry = $this->getFATFByQuestion($aEighteen);
        $this->sumFATFSix = ($aSixBool == Question::YES) && ($aNineCountry || $aTenCountry || $aElevenCountry || $aEighteenCountry);

        return $this->sumFATFSix;

    }

}