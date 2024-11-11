<?php

// app/Mail/ReservedItemArrived.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservedItemArrived extends Mailable
{
    use Queueable, SerializesModels;

    public $reservedItem;

    public function __construct($reservedItem)
    {
        $this->reservedItem = $reservedItem;
    }

    public function build()
    {
        return $this->subject('Your Reserved Item Has Arrived')
                    ->view('emails.reserved_item_arrived')
                    ->with([
                        'reservedItem' => $this->reservedItem,
                        'user' => $this->reservedItem->user,
                        'product' => $this->reservedItem->product,
                    ]);
    }
}
