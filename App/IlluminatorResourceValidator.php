<?php

declare(strict_types=1);

namespace App\Domains\Illuminator;

use App\Core\Validation\CommonRules;
use App\Core\Validation\ResourceValidator;
use App\Domains\Core\CostModel;
use Illuminate\Validation\Rule;

class IlluminatorResourceValidator extends ResourceValidator
{
    protected function rules(array $rawData, array $options): array
    {
        return [
                'name' => ['required', ...CommonRules::name()],
                'website' => ['nullable', 'url'],
                'webhookUrl' => ['nullable', 'url'],
                'defaultRevenueModel' => ['nullable', Rule::in([
                    CostModel::REVENUE_SHARE,
                    CostModel::CPC,
                    CostModel::CPM,
                ])],
                'defaultCostValue' => ['nullable', ...CommonRules::money(0.01)],
            ]
            + CommonRules::address('contactAddress')
            + CommonRules::address('billingAddress')
            + CommonRules::address('technicalAddress');
    }
}
