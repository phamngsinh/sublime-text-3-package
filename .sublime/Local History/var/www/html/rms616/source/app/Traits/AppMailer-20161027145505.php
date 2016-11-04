<?php

namespace App\Traits;

use App\Models\User;
use Mail;
trait AppMailer
{

    /**
     * The sender of the email.
     *
     * @var string
     */
    protected $from = 'risk@risk24.com';
    /**
     * The recipient of the email.
     *
     * @var string
     */
    protected $to;
    /**
     * The view for the email.
     *
     * @var string
     */
    protected $view;
    /**
     * The data associated with the view for the email.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create a new app mailer instance.
     *
     * @param Mailer $mailer
     */


    /**
     * Deliver the email confirmation.
     *
     * @param  User $user
     * @return void
     */
    public function sendEmailConfirmationTo(User $user)
    {
        $this->to = $user->email;
        $this->view = 'mails.register';
        $this->data = compact('user');
        $this->deliver();
    }

    /**
     * Deliver the email.
     *
     * @return void
     */
    public function deliver()
    {
        Mail::send('emails.register',$this->data, function ($m)  {
            $m->from($this->from, 'Greetings from Risk24.org');
            $m->to($this->to)->subject('Greetings from Risk24.org!');
        });
    }
}