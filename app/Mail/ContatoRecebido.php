<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContatoRecebido extends Mailable
{
    use Queueable, SerializesModels;

    public string $nomeRemetente;
    public string $emailRemetente;
    public string $assunto;
    public string $mensagem;

    public function __construct(string $nomeRemetente, string $emailRemetente, string $assunto, string $mensagem)
    {
        $this->nomeRemetente  = $nomeRemetente;
        $this->emailRemetente = $emailRemetente;
        $this->assunto        = $assunto;
        $this->mensagem       = $mensagem;
    }

    public function build()
    {
        return $this->subject('[Contato Paróquia] ' . $this->assunto)
                    ->replyTo($this->emailRemetente, $this->nomeRemetente)
                    ->view('emails.contato');
    }
}
