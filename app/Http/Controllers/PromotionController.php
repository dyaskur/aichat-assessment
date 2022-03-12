<?php

namespace App\Http\Controllers;

use App\Exceptions\FailResponse;
use App\Http\Requests\PromotionCheckRequest;
use App\Http\Requests\ValidatePhotoRequest;
use App\Models\Promotion;
use App\Models\PromotionCode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{

    /**
     * @throws FailResponse
     */
    public function eligibleCheck(PromotionCheckRequest $request): JsonResponse
    {
        //find the active promotion with requested code
        $promotion = (new Promotion)->findByCode($request->code);

        $customer = $request->customer();

        $isEligible = $promotion->eligibleCheck($customer);
        DB::beginTransaction();
        try {
            $promotionCode = $promotion->lockAvailableCode();
            $promotionCode->lockForCustomer($customer);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new FailResponse(
                  'Something is wrong, please try again later'
                , 500, $e
            );
        }

        return response()->json(['isEligible' => $isEligible, 'code' => $promotionCode]);
    }

    //

    /**
     * @throws FailResponse
     */
    public function claim(ValidatePhotoRequest $request): JsonResponse
    {
        $customer  = $request->customer();
        $promotion = (new Promotion)->findByCode($request->code);

        $code     = $promotion->findCodeByLockedFor($customer->id);
        $filepath = null;
        DB::beginTransaction();
        try {
            $isRecognized = $request->isRecognized();
            if ($isRecognized) {
                $code->claimForCustomer($customer);
                $filepath = $customer->uploadImage($request->photo);
            } else {
                $code->unlock();
                DB::commit();//unlock the code by set locked_for & locked_until to null
                throw new FailResponse(
                    'Your photo validation is invalid, the booked code is released to other customers. but you can book it again'
                );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            if ($filepath) {
                Storage::delete($filepath);
            }
            throw new FailResponse(
                  'Something is wrong, please try again later'
                , 500, $e
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
