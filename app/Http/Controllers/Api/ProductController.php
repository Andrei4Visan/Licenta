<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Api\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field','updated_at');
        $sortDirection = request('sort_directions','desc');
        $query = Product::query();
        $query->orderBy($sortField,$sortDirection);
        if($search){
            $query->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
        }
        return ProductListResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ProductRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(ProductRequest $request)
    {
       $data = $request->validated();
       $data['created_by'] = $request->user()->id;
       $data['updated_by'] = $request->user()->id;
       /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;

        // vom verifica daca imaginea a fost data si o vom salva in local fule system

        if($image){
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }
        $product = Product::create($data);
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;

        if($image){
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();

            if($product->image){
                Storage::deleteDirectory('/public/' . dirname($product->image));

            }
        }
        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();

    }

    private function saveImage(\Illuminate\Http\UploadedFile $image): string
    {
        $path = 'images/' . Str::random();
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0755, true);
        }
        if(!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())){
            throw new \Exception("Imposibil de a salva fisierul \"{$image->getClientOriginalName()}\"");
        }
        return $path . '/' . $image->getClientOriginalName();
    }
}
