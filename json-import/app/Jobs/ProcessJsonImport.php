<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessJsonImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $jsonUsersData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($jsonUsersData)
    {
        $this->jsonUsersData = $jsonUsersData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->jsonUsersData as $userData) {
            $date_of_birth = null;

            if ($userData['date_of_birth'] != null) {

                if (str_contains($userData['date_of_birth'], '/') && is_numeric(substr($userData['date_of_birth'], -4))) {
                    $date_of_birth = Carbon::createFromFormat('m/d/Y', $userData['date_of_birth'])->format('Y-m-d');
                } else {
                    $date_of_birth = Carbon::parse($userData['date_of_birth']);
                }
//            kijken of leeftijd tussen 18 en 65 is.
                if ($date_of_birth > Carbon::now()->subYears(18) && $date_of_birth < Carbon::now()->subYears(65)) {
                    continue;
                }
            }

            $user = User::query()->where('email', '=', $userData['email'])->first();
            if ($user == null) {
                $user = User::query()->create([
                    'name' => $userData['name'],
                    'address' => $userData['address'],
                    'checked' => $userData['checked'],
                    'description' => $userData['description'],
                    'interest' => $userData['interest'],
                    'date_of_birth' => $date_of_birth,
                    'email' => $userData['email'],
                    'account' => $userData['account'],
                ]);
            }
            if ($user->creditCard()->count() == 0) {
                $user->creditCard()->create([
                    'user_id' => $user->id,
                    'type' => $userData['credit_card']['type'],
                    'number' => $userData['credit_card']['number'],
                    'name' => $userData['credit_card']['name'],
                    'expirationDate' => $userData['credit_card']['expirationDate'],
                ]);
            }
        }
    }
}
