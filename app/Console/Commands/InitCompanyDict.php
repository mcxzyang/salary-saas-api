<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\DictItem;
use Illuminate\Console\Command;

class InitCompanyDict extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:company-dict';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dictItemList = DictItem::query()->where('is_system', 1)->whereNull('company_id')->get();
        $companyList = Company::query()->get();

        foreach ($companyList as $company) {
            foreach ($dictItemList as $dictItem) {
                $check = DictItem::query()->where(['dict_id' => $dictItem->dict_id, 'value' => $dictItem->value, 'company_id' => $company->id])->first();
                if (!$check) {
                    $newItem = $dictItem->replicate();
                    $newItem->company_id = $company->id;
                    $newItem->is_system = 0;
                    $newItem->save();
                }
            }
        }
    }
}
