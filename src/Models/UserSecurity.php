<?php

namespace Pagelyne\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSecurity extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'user_security';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',

        'failed_login_attempts',
        'last_failed_login_at',
        'locked_until',

        'is_locked',
        'locked_at',
        'lock_reason',

        'password_changed_at',
        'password_expires_at',
        'password_change_required',
        'password_history_limit',

        'two_factor_required',

        'email_verification_required',
        'phone_verification_required',

        'security_alerts_enabled',
        'last_security_alert_at',

        'last_password_reset_at',
        'last_security_verification_at',

        'revoke_sessions_on_password_change',
        'revoke_tokens_on_password_change',

        'metadata',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            /*
             * Login Security
             */
            'failed_login_attempts' => 'integer',
            'last_failed_login_at' => 'datetime',
            'locked_until' => 'datetime',

            /*
             * Account Lock
             */
            'is_locked' => 'boolean',
            'locked_at' => 'datetime',

            /*
             * Password Security
             */
            'password_changed_at' => 'datetime',
            'password_expires_at' => 'datetime',
            'password_change_required' => 'boolean',
            'password_history_limit' => 'integer',

            /*
             * Authentication
             */
            'two_factor_required' => 'boolean',

            /*
             * Verification
             */
            'email_verification_required' => 'boolean',
            'phone_verification_required' => 'boolean',

            /*
             * Security Alerts
             */
            'security_alerts_enabled' => 'boolean',
            'last_security_alert_at' => 'datetime',

            /*
             * Credential Recovery
             */
            'last_password_reset_at' => 'datetime',
            'last_security_verification_at' => 'datetime',

            /*
             * Session Security
             */
            'revoke_sessions_on_password_change' => 'boolean',
            'revoke_tokens_on_password_change' => 'boolean',

            /*
             * Metadata
             */
            'metadata' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User who owns this security record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}