<?php
/**
 * get question via question code in cache
 */
function question_by_code($questionCode)
{

    if (!\Cache::has('risks.questions'.$questionCode)) {
        $question = App\Models\Question::with(['questionOptions'])->where('question_code', $questionCode)->first();
        \Cache::put('risks.questions'.$questionCode, $question, 10);
    }
    $question = \Cache::get('risks.questions'.$questionCode);

    return $question;
}

/**
 * Parse int values of array
 * @param array $params
 * @return array
 */
function parseIntValuesOfArray(array $params){
    return array_map('intval', $params);
}

/**
 * render option selection
 */
function render_selection_question($questionCode)
{
    $question = question_by_code($questionCode);
    $str = '<p> <span class="title-question">'.$question->question_code.'.</span>';
    $str .= $question->descriptions;
    $str .= $question->require ? '<span class="text-red">* </span>' : '';
    $str .= '</p>';
    $str .= '<select class="full-width" name="'.$question->question_code.'">';
    if (in_array($questionCode, App\Models\Question::$listQuestionCountry)) {
        $question->questionOptions = App\Models\Country::all();
        $str = format_country_selective($str, $question);

    }
    elseif (in_array($questionCode, App\Models\Question::$listActivityCountry)) {
        $question->questionOptions = App\Models\Activity::all();
        $str = format_activity_selective($str, $question);
    }
    else {

        $str = format_selective($str, $question);
    }
    $str .= '</select>';
    echo $str;
}

/**
 * custom select dom
 */
function format_selective($str, $question)
{
    foreach ($question->questionOptions as $element) {
        if ($question->default_value === $element->id) {
            $str .= '<option selected value="'.$element->id.'">'.$element->value.'</option>';
        }
        else {
            $str .= '<option  value="'.$element->id.'">'.$element->value.'</option>';
        }
    }

    return $str;
}

/**
 * custom select dom
 */
function format_country_selective($str, $question)
{
    foreach ($question->questionOptions as $element) {
        if ($question->default_value === $element->id) {
            $str .= '<option selected value="'.$element->name.'">'.$element->long_name.'</option>';
        }
        else {
            $str .= '<option  value="'.$element->name.'">'.$element->long_name.'</option>';
        }
    }

    return $str;
}

/**
 * custom select dom
 */
function format_activity_selective($str, $question)
{
    foreach ($question->questionOptions as $element) {
        if ($question->default_value === $element->id) {
            $str .= '<option selected value="'.$element->id.'">'.$element->name.'</option>';
        }
        else {
            $str .= '<option  value="'.$element->id.'">'.$element->name.'</option>';
        }
    }

    return $str;
}

/**
 * render input text
 */

function render_text_question($questionCode)
{
    $question = question_by_code($questionCode);
    $str = '<p> <span class="title-question">'.$question->question_code.'.</span>';
    $str .= $question->descriptions;
    $str .= $question->require ? '<span class="text-red">*</span>' : '';
    $str .= '</p>';
    $str .= '<div class="row-info">';
    $require = $question->require ? 'required' : '';
    $str .= '<input '.$require.'  type="text" name="'.$question->question_code.'" class="size-12 full-width font-lato-r size-12 form-control">';
    $str .= '</div>';
    echo $str;
}

/**
 * render input text
 */
function render_number_question($questionCode)
{
    $question = question_by_code($questionCode);
    $str = '<p> <span class="title-question">'.$question->question_code.'.</span>';
    $str .= $question->descriptions;
    $str .= $question->require ? '<span class="text-red">*</span>' : '';
    $str .= '</p>';
    $str .= '<div class="row-info">';
    $require = $question->require ? 'required' : '';
    $str .= '<input '.$require.'  type="number" name="'.$question->question_code.'" class="size-12 full-width font-lato-r size-12 form-control">';
    $str .= '</div>';
    echo $str;
}

/**
 * render input bool
 */

function render_bool_question($questionCode)
{
    $question = question_by_code($questionCode);
    $str = '<p> <span class="title-question">'.$question->question_code.'.</span>';
    $str .= $question->descriptions;
    $str .= $question->require ? '<span class="text-red">* </span>' : '';
    $str .= '</p>';
    foreach ($question->questionOptions as $element) {
        $pull_right = ($element->value == 'Yes') ? '' : 'pull-right';
        $checked = ($question->default_value === $element->id) ? 'checked' : '';
        $str .= '<label class="control control--radio '.$pull_right.'">'.$element->value;
        $str .= '<input type="radio" name="'.$question->question_code.'" value="'.$element->id.'" '.$checked.'>';
        $str .= '<div class="control__indicator"></div></label>';
    }

    echo $str;
}

/**
 * @param $price
 * @return string
 */
function render_price($price){
    return config('currency.symbol_value')['USD'].$price.'/year';
}

/**
 * @param $price
 * @return string
 */
function render_total($price){
    return config('currency.symbol_value')['USD'].$price;
}
function amount_due($price_plan,$credit_unused){
    return $price_plan - $credit_unused;
}