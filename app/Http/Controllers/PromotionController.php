<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionCheckRequest;
use App\Http\Requests\ValidatePhotoRequest;
use App\Models\Promotion;
use App\Models\PromotionCode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{

    public function eligibleCheck(PromotionCheckRequest $request): JsonResponse
    {
        //find the active promotion with requested code
        $promotion = (new Promotion)->findByCode($request->code);
        if (!$promotion) {
            return response()->json(['message' => 'Invalid/expired promotion code'], 422);
        }
        $customer = $request->customer();

        $isEligible = $promotion->eligibleCheck($customer);
        DB::beginTransaction();
        try {
            $promotionCode = $promotion->codes()->available()->lockForUpdate()->first();
            $promotionCode->lockForCustomer($customer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(
                [
                    'message'   => 'Internal server error',
                    'exception' => $e->getMessage(),
                ],
                500
            );
        }

        return response()->json(['isEligible' => $isEligible, 'code' => $promotionCode]);
    }

    //
    public function claim(ValidatePhotoRequest $request): JsonResponse
    {
        $customer  = $request->customer();
        $promotion = (new Promotion)->findByCode($request->code);

        if (!$promotion) {
            return response()->json(['message' => 'Invalid/expired promotion code', "code" => $request->code], 422);
        }
        $code = $promotion->findCodeByLockedFor($customer->id);


        DB::beginTransaction();
        try {
            $isRecognized = $request->isRecognized();
            $code->claimForCustomer($customer);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(
                [
                    'message' => env("APP_DEBUG") ? $e->getMessage() : 'Internal server error',
                ],
                500
            );
        }

        return response()->json(['is_recognized' => $isRecognized, 'code' => $code]);
    }

    //
    public function test(): JsonResponse
    {
        //test pessimistic lock
        $promotionCode = PromotionCode::query()->find(1);

        return response()->json(['code' => $promotionCode]);
    }

}
