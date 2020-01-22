<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            ['id'=>1, 'name'=>'amrulloh','email'=>'izratool@gmail.com','password'=>'abdulloh123','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['id'=>2, 'name'=>'kholiq amrulloh','email'=>'kholiq@gmail.com','password'=>'abdulloh06','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        );
        foreach($data as $key => $user){
            DB::table('users')->insert($user);
            DB::table("users")
            ->where("id", $user['id'])
            ->update(array("password"=>Hash::make($user['password'])));
        }
    }
}
