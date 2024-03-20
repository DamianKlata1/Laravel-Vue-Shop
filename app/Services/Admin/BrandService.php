<?php

namespace App\Services\Admin;

use App\Models\Brand;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
    public function getBrands(): LengthAwarePaginator
    {
        return Brand::paginate(10)->withQueryString();
    }

    /**
     * @throws \Exception
     */
    public function createBrand(array $data): void
    {
        try {
            $brand = new Brand();
            $brand->name = $data['name'];
            $brand->save();
        } catch (\Exception $e) {
            throw new \Exception('Brand could not be created: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function updateBrand(int $brandId, array $data): void
    {
        try {
            $brand = Brand::find($brandId);
            $brand->name = $data['name'];
            $brand->save();
        } catch (\Exception $e) {
            throw new \Exception('Brand could not be updated: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteBrand(int $brandId): void
    {
        try {
            $brand = Brand::find($brandId);
            $brand->delete();
        } catch (\Exception $e) {
            throw new \Exception('Brand could not be deleted: ' . $e->getMessage());
        }
    }

}
