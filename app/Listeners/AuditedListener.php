<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use OwenIt\Auditing\Events\Auditing;
use App\Models\GmodBans;
use OwenIt\Auditing\Events\Audited;

class AuditedListener
{
    /**
     * Create the Audited event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the Audited event.
     *
     * @param \OwenIt\Auditing\Events\Audited $event
     * @return void
     */
    public function handle(Audited $event)
    {
        $audit = $event->audit;
        $auditMeta = $audit->getMetadata();
        if ($auditMeta["audit_event"] !== 'updated') {
            return;
        }

        $model = $event->model;
        if (! $model instanceof GmodBans) {
            return;
        }
        $modified = $audit->getModified();
        $wasModified = false;
        if (array_key_exists('Reason', $modified) && $model->ReasonEdited !== false) {
            $model->ReasonEdited = true;
            $wasModified = true;
        }

        if (array_key_exists('ExpiryDate', $modified) && $model->ExpiryDateEdited !== false) {
            $model->ExpiryDateEdited = true;
            $wasModified = true;
        }

        if ($wasModified) {
            $model->saveQuietly();
        }
    }
}