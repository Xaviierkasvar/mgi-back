<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    private const PRODUCT_NOT_FOUND = 'Product not found';

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Retrieve a list of products",
     *     description="Fetches all the products in the database.",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to fetch products"
     *     )
     * )
     */
    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch products'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     description="Creates a new product in the system.",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "stock"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer", minimum=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to create product"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $response = null;
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer|min:0',
            ]);

            $product = $this->productService->createProduct($request->all());
            $response = response()->json($product, 201);
        } catch (ValidationException $e) {
            $response = response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Unable to create product'], 500);
        }

        return $response;
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Retrieve a product by ID",
     *     description="Fetches a single product by its ID.",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to fetch",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to fetch product"
     *     )
     * )
     */
    public function show($id)
    {
        $response = null;
        try {
            $product = $this->productService->getProductById($id);
            $response = response()->json($product);
        } catch (ModelNotFoundException $e) {
            $response = response()->json(['error' => self::PRODUCT_NOT_FOUND], 404);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Unable to fetch product'], 500);
        }

        return $response;
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update an existing product",
     *     description="Updates the details of a product by its ID.",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer", minimum=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to update product"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $response = null;
        try {
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'stock' => 'sometimes|required|integer|min:0',
            ]);

            $product = $this->productService->updateProduct($id, $request->all());
            $response = response()->json($product, 200);
        } catch (ValidationException $e) {
            $response = response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            $response = response()->json(['error' => self::PRODUCT_NOT_FOUND], 404);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Unable to update product'], 500);
        }

        return $response;
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product",
     *     description="Deletes a product by its ID.",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to delete product"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => self::PRODUCT_NOT_FOUND], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete product'], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/products/{id}/stock",
     *     summary="Update product stock",
     *     description="Updates the stock level of a product.",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="stock", type="integer", minimum=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stock updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Unable to update stock"
     *     )
     * )
     */
    public function updateStock(Request $request, $id)
    {
        $response = null;
        try {
            $request->validate([
                'stock' => 'required|integer|min:0',
            ]);

            $product = $this->productService->updateStock($id, $request->input('stock'));
            $response = response()->json($product);
        } catch (ValidationException $e) {
            $response = response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            $response = response()->json(['error' => self::PRODUCT_NOT_FOUND], 404);
        } catch (\Exception $e) {
            $response = response()->json(['error' => 'Unable to update stock'], 500);
        }

        return $response;
    }
}