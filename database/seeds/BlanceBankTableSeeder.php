<?php

use Illuminate\Database\Seeder;

class BlanceBankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            ['id'=>1, 'balance'=>1000000,'balance_achieve'=>1000000,'code'=>'AA300002','enable'=>1,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['id'=>2, 'balance'=>1500000,'balance_achieve'=>1500000,'code'=>'AA200001','enable'=>1,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
        );
        foreach($data as $key => $bank){
            DB::table('blance_bank')->insert($bank);
           
        }
    }
}
