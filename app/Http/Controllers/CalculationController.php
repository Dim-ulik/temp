<?php

namespace App\Http\Controllers;

use App\Models\Calculation\BusinessCard;
use App\Models\Calculation\DiscountCard;
use App\Models\Calculation\Leaflet;
use App\Models\Calculation\Postcard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class CalculationController extends Controller
{
    private function getQuery($itemType) {
        switch ($itemType) {
            case 'businessCard':
                return BusinessCard::query();
            case 'postcard':
                return Postcard::query();
            case 'leaflet':
                return Leaflet::query();
            case 'discountCard':
                return DiscountCard::query();
            default:
                return null;
        }
    }

    private function createResponseFromPricesArray($array) {
        $response = [];
        foreach ($array as $item) {
            $response[$item['type']] = $item['price'];
        }
        return $response;
    }

    function getPrice($item) {
        try {
            $array = $this->getQuery($item)->get();
            return $this->createResponseFromPricesArray($array);
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }

    function changePrice(Request $request, $item) {
        try {
            $query = $this->getQuery($item)->where('type', $request->get('type'));
            if (!$query->exists()) {
                return $this->returnBadResponse(404, 'Undefined type');
            }

            $validatedData = $request->validate([
                'price' => 'required|numeric'
            ]);

            $query->update([
                'price' => $validatedData['price'],
            ]);

            return response('', 200);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }
}
