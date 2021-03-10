<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Auth\User;
use App\Domains\Core\AddressHelper;
use App\Domains\Core\CostModel;
use App\Domains\Illuminator\Actions\UpsertIlluminator;
use App\Domains\Illuminator\Illuminator;
use App\Domains\Illuminator\IlluminatorResourceValidator;
use App\Http\Resources\BondResource;
use App\Http\Resources\IlluminatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class IlluminatorController extends Controller
{
    public function index(Request $request)
    {
        /** @var Illuminator|User $user */
        $user = $request->user();
        if ($user instanceof Illuminator) {
            return IlluminatorResource::collection(Arr::wrap($user));
        }
        if (!$user->isAdmin()) {
            return IlluminatorResource::collection(
                $user->illuminatorAccounts()
                    ->skip($request->collectionOffset())
                    ->limit($request->collectionLimit())
                    ->get()
            );
        }

        return IlluminatorResource::collection(
            Illuminator::skip($request->collectionOffset())
                ->limit($request->collectionLimit())
                ->get()
        );
    }

    public function store(Request $request, IlluminatorResourceValidator $validator, UpsertIlluminator $action)
    {
        $data = $validator->validate($request->all());
        $this->authorize('create', Illuminator::class);
        /** @var User $user */
        $user = $request->user();
        $addressDefaults = [
            'contactName' => $user->name,
            'contactNumber' => $user->contactNumber,
            'contactEmail' => $user->email,
        ];

        return IlluminatorResource::make(
            $action->execute(
                new Illuminator(),
                $data['name'],
                Arr::get($data, 'website'),
                Arr::get($data, 'webhookUrl'),
                AddressHelper::extractFromField($data, 'contactAddress', $addressDefaults),
                AddressHelper::extractFromField($data, 'billingAddress', $addressDefaults),
                AddressHelper::extractFromField($data, 'technicalAddress', $addressDefaults),
                $user,
                $data['defaultCostModel'] ?? CostModel::REVENUE_SHARE,
                (float) ($data['defaultCostValue'] ?? 0.6)
            )
        );
    }

    public function update(Illuminator $illuminator, Request $request, IlluminatorResourceValidator $validator, UpsertIlluminator $action)
    {
        $data = $validator->validate($request->all());
        $this->authorize('update', $illuminator);
        /** @var User $user */
        $user = $request->user();
        $addressDefaults = [
            'contactName' => $user->name,
            'contactNumber' => $user->contactNumber,
            'contactEmail' => $user->email,
        ];

        return IlluminatorResource::make(
            $action->execute(
                $illuminator,
                $data['name'],
                Arr::get($data, 'website'),
                Arr::get($data, 'webhookUrl'),
                AddressHelper::extractFromField($data, 'contactAddress', $addressDefaults),
                AddressHelper::extractFromField($data, 'billingAddress', $addressDefaults),
                AddressHelper::extractFromField($data, 'technicalAddress', $addressDefaults),
                $user,
                $data['defaultCostModel'] ?? CostModel::REVENUE_SHARE,
                (float) ($data['defaultCostValue'] ?? 0.6)
            )
        );
    }

    public function show(Illuminator $illuminator): JsonResource
    {
        $this->authorize('read', $illuminator);

        return IlluminatorResource::make($illuminator);
    }

    public function showBond(Illuminator $illuminator): JsonResource
    {
        $this->authorize('read', $illuminator);

        return BondResource::make($illuminator->bond);
    }

    public function timeline(Illuminator $target, ControllerTimelineResponseHelper $helper)
    {
        $this->authorize('read', $target);

        return $helper->execute($target);
    }
}
