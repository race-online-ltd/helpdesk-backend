<?php

namespace App\Logic;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserClientMapping;
use App\Models\UserEntityMapping;
use App\Models\Company;
use App\Models\CustomerParentMapping;
use Illuminate\Support\Facades\DB;

class Client
{
    public static function getClientIdGeneratedBySystem($clientData)
    {
        $userId = UserClientMapping::where('business_entity_id', $clientData['businessEntity'])
            ->where('client_id', $clientData['vendorId'])
            ->value('user_id');

        return $userId ?? null;
    }

    public static function createClient($clientData)
    {


        $exist = UserClientMapping::where('business_entity_id', $clientData['businessEntity'])
            ->where('client_id', $clientData['client'])
            ->first();
        if ($exist) {
            return $exist->user_id;
        }

        $userStore = User::create([
            'username' => $clientData['userName'],
            'password' => bcrypt($clientData['password']),
            'status' => $clientData['status'],
        ]);
        $userId = $userStore->id;
        
        $userProfileStore = UserProfile::create([
            'user_id' => $userId,
            'user_type' => $clientData['userType'],
            'fullname' => $clientData['fullName'],
            'email_primary' => $clientData['primaryEmail'],
            'email_secondary' => $clientData['secondaryEmail'],
            'mobile_primary' => $clientData['primaryPhone'],
            'mobile_secondary' => $clientData['secondaryPhone'],
            'role_id' => $clientData['role'],
            'default_entity_id' => $clientData['defaultBusinessEntity'],
            'one_time_password' => $clientData['password'],
            'lock' => $clientData['lock'],
        ]);

        

        UserEntityMapping::create([
            'business_entity_id' => $clientData['businessEntity'],
            'user_id' => $userId
        ]);


        UserClientMapping::create([
            'business_entity_id' => $clientData['businessEntity'],
            'user_id' => $userId,
            'client_id' => $clientData['client'],
            'client_name' => $clientData['clientName'],
        ]);

        return $userId;
    }

    // public static function createChildClient($clientData)
    // {


    //     $exist = UserClientMapping::where('business_entity_id', $clientData['businessEntity'])
    //         ->where('client_id', $clientData['client'])
    //         ->first();
    //     if ($exist) {
    //         return $exist->user_id;
    //     }

    //     $userStore = User::create([
    //         'user_type' => $clientData['userType'],
    //         'username' => $clientData['userName'],
    //         'fullname' => $clientData['fullName'],
    //         'email_primary' => $clientData['primaryEmail'],
    //         'email_secondary' => $clientData['secondaryEmail'],
    //         'mobile_primary' => $clientData['primaryPhone'],
    //         'mobile_secondary' => $clientData['secondaryPhone'],
    //         'role_id' => $clientData['role'],
    //         'default_entity_id' => $clientData['defaultBusinessEntity'],
    //         'password' => bcrypt($clientData['password']),
    //         'one_time_password' => $clientData['password'],
    //         'lock' => $clientData['lock'],
    //         'status' => $clientData['status'],
    //     ]);

    //     $userId = $userStore->id;

    //     UserEntityMapping::create([
    //         'business_entity_id' => $clientData['businessEntity'],
    //         'user_id' => $userId
    //     ]);


    //     UserClientMapping::create([
    //         'business_entity_id' => $clientData['businessEntity'],
    //         'user_id' => $userId,
    //         'client_id' => $clientData['client'],
    //         'client_name' => $clientData['clientName'],
    //     ]);

    //     return $userId;
    // }





    public static function createChildClient($clientData)
    {
        $exist = UserClientMapping::where('business_entity_id', $clientData['businessEntity'])
            ->where('client_id', $clientData['client'])
            ->first();

        if ($exist) {
            return $exist->user_id;
        }

        DB::transaction(function () use ($clientData, &$userId) {

            $user = User::create([
                'username'          => $clientData['userName'],
                'password'          => bcrypt($clientData['password']),
                'one_time_password' => $clientData['password'],
                'status'            => $clientData['status'],
            ]);

            $userId = $user->id;

            UserProfile::create([
                'user_id'               => $userId,
                'user_type'             => $clientData['userType'],
                'fullname'              => $clientData['fullName'],
                'email_primary'         => $clientData['primaryEmail'],
                'email_secondary'       => $clientData['secondaryEmail'],
                'mobile_primary'        => $clientData['primaryPhone'],
                'mobile_secondary'      => $clientData['secondaryPhone'],
                'role_id'               => $clientData['role'],
                'default_entity_id'     => $clientData['defaultBusinessEntity'],
                'one_time_password'     => $clientData['password'],
                'password_change'       => 0,
                'status'                => $clientData['status'],
            ]);

            UserEntityMapping::create([
                'business_entity_id' => $clientData['businessEntity'],
                'user_id'            => $userId,
            ]);

            UserClientMapping::create([
                'business_entity_id' => $clientData['businessEntity'],
                'user_id'            => $userId,
                'client_id'          => $clientData['client'],
                'client_name'        => $clientData['clientName'],
            ]);
        });

        return $userId;
    }

    public static function isClienExist($clientId) {}


    //SuperApp Client

        public static function generateUsername($businessEntityId)
  {
          $businessEntity = Company::findOrFail($businessEntityId);
          $prefix = $businessEntity->prefix;

          $lastUser = User::join('user_profiles as up', 'up.user_id', '=', 'users.id')
              ->where('up.user_type', 'Client')
              ->where('users.username', 'LIKE', $prefix . '-%') // ✅ important fix
              ->orderBy('users.username', 'desc')
              ->select('users.username')
              ->first();

          $number = 1;

          if ($lastUser && str_contains($lastUser->username, '-')) {
              $parts = explode('-', $lastUser->username);
              $number = intval($parts[1]) + 1;
          }

          return $prefix . '-' . sprintf("%05d", $number);


  }

    public static function generateStrongPassword($length = 8)
  {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '@$!%*?&';

        $password = [
            $upper[rand(0, strlen($upper) - 1)],
            $lower[rand(0, strlen($lower) - 1)],
            $numbers[rand(0, strlen($numbers) - 1)],
            $special[rand(0, strlen($special) - 1)],
        ];

        $all = $upper . $lower . $numbers . $special;

        for ($i = count($password); $i < $length; $i++) {
            $password[] = $all[rand(0, strlen($all) - 1)];
        }

        shuffle($password);

        return implode('', $password);

  }

   public static function createSuperAppClient($clientData)
    {
        $exist = UserClientMapping::where('business_entity_id', $clientData['businessEntity'])
            ->where('client_id', $clientData['client'])
            ->first();

        if ($exist) {
            return $exist->user_id;
        }

        // $existingUser = User::where('username', $clientData['username'])->first();
        //  if ($existingUser) { 
        //   return $existingUser->id; 
        //  }

        DB::transaction(function () use ($clientData, &$userId) {

            $username = self::generateUsername($clientData['businessEntity']);
            $password = self::generateStrongPassword();

            $user = User::create([
            'username'          => $clientData['username'],
            'password'          => bcrypt($password),
            'status'            => $clientData['status'],
            ]);


            $userId = $user->id;

            UserProfile::create([
                'user_id'               => $userId,
                'user_type'             => $clientData['userType'],
                'entity_type'           => $clientData['entityType'],
                'fullname'              => $clientData['fullName'],
                'email_primary'         => $clientData['primaryEmail'],
                'email_secondary'       => $clientData['secondaryEmail'],
                'mobile_primary'        => $clientData['primaryPhone'],
                'mobile_secondary'      => $clientData['secondaryPhone'],
                'role_id'               => $clientData['role'],
                'default_entity_id'     => $clientData['defaultBusinessEntity'],
                'one_time_password'     => 123456,
                'password_change'       => 0,
            ]);

            UserEntityMapping::create([
                'business_entity_id' => $clientData['businessEntity'],
                'user_id'            => $userId,
            ]);

            UserClientMapping::create([
                'business_entity_id' => $clientData['businessEntity'],
                'user_id'            => $userId,
                'client_id'          => $clientData['client'],
                'client_name'        => $clientData['clientName'],
            ]);

            CustomerParentMapping::firstOrCreate(
            ['user_id' => $userId],
            [
            'client_id'   => $clientData['client'],
            'client_name' => $clientData['clientName'],
            ]
            );
        });

        

        return $userId;
    }

}
