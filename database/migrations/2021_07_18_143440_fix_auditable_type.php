<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Audit as AuditModel;

class FixAuditableType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (AuditModel::cursor() as $audit) {
            $old = $input_lines = $audit->auditable_type;

            $audit->auditable_type = preg_replace(
                '/(.*?App\\\\)(.*?)\\\\((.*?)Models\\\\.*)/',
                '$1$3',
                $input_lines
            );

            $old_user_type = $input_lines = $audit->user_type;

            $audit->user_type = preg_replace(
                '/(.*?App\\\\)(.*?)\\\\((.*?)Models\\\\.*)/',
                '$1$3',
                $input_lines
            );

            $audit->save();

            dump(
                'Updating auditable_type from audit id = ' .
                    $audit->id .
                    ' from ' .
                    $old .
                    ' to ' .
                    $audit->auditable_type .
                    ' and user_type from ' .
                    $old_user_type .
                    ' to ' .
                    $audit->user_type
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Can't go back
    }
}
