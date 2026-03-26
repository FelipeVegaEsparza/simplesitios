<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreApiController extends Controller
{
    /**
     * Listar productos del catálogo
     */
    public function catalog(Request $request, $clientSlug)
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->where('store_enabled', true)
            ->firstOrFail();

        $query = $client->products()
            ->active()
            ->where('status', 'active')
            ->with(['category', 'variants' => function ($q) {
                $q->where('status', 'active');
            }]);

        // Filtro por categoría
        if ($request->filled('category')) {
            $category = ProductCategory::where('slug', $request->category)
                ->where('client_id', $client->id)
                ->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order')->paginate($request->input('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => [
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'slug' => $client->slug,
                    'description' => $client->description,
                    'logo' => $client->logo ? Storage::disk('public')->url($client->logo) : null,
                ],
                'products' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'description' => $product->description,
                        'base_price' => $product->base_price,
                        'formatted_price' => $product->formatBasePrice(),
                        'sku' => $product->sku,
                        'has_variants' => $product->has_variants,
                        'total_stock' => $product->total_stock,
                        'track_stock' => $product->track_stock,
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'name' => $product->category->name,
                            'slug' => $product->category->slug,
                        ] : null,
                        'images' => collect($product->images ?? [])->map(function ($image) {
                            return Storage::disk('public')->url($image);
                        }),
                        'variants' => $product->variants->map(function ($variant) {
                            return [
                                'id' => $variant->id,
                                'name' => $variant->variant_name,
                                'sku' => $variant->sku,
                                'price' => $variant->getFinalPrice(),
                                'formatted_price' => $variant->formatPrice(),
                                'stock' => $variant->stock,
                                'in_stock' => $variant->isInStock(),
                            ];
                        }),
                    ];
                }),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ],
        ]);
    }

    /**
     * Detalle de un producto
     */
    public function product($clientSlug, $productSlug)
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->where('store_enabled', true)
            ->firstOrFail();

        $product = $client->products()
            ->where('slug', $productSlug)
            ->where('status', 'active')
            ->with(['category', 'variants' => function ($q) {
                $q->where('status', 'active');
            }])
            ->firstOrFail();

        // Productos relacionados
        $relatedProducts = $client->products()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'base_price' => $product->base_price,
                    'formatted_price' => $product->formatBasePrice(),
                    'sku' => $product->sku,
                    'has_variants' => $product->has_variants,
                    'total_stock' => $product->total_stock,
                    'track_stock' => $product->track_stock,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ] : null,
                    'images' => collect($product->images ?? [])->map(function ($image) {
                        return Storage::disk('public')->url($image);
                    }),
                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'name' => $variant->variant_name,
                            'sku' => $variant->sku,
                            'price' => $variant->getFinalPrice(),
                            'formatted_price' => $variant->formatPrice(),
                            'stock' => $variant->stock,
                            'in_stock' => $variant->isInStock(),
                        ];
                    }),
                ],
                'related_products' => $relatedProducts->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'base_price' => $p->base_price,
                        'formatted_price' => $p->formatBasePrice(),
                        'image' => $p->images && count($p->images) > 0 ? Storage::disk('public')->url($p->images[0]) : null,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Listar categorías
     */
    public function categories($clientSlug)
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->where('store_enabled', true)
            ->firstOrFail();

        $categories = $client->productCategories()
            ->active()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                ];
            }),
        ]);
    }

    /**
     * Crear una orden (checkout)
     */
    public function createOrder(Request $request, $clientSlug)
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->where('store_enabled', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_region' => 'nullable|string|max:100',
            'shipping_zip' => 'nullable|string|max:20',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verificar que la cuenta bancaria pertenece al cliente
        $bankAccount = BankAccount::where('id', $request->bank_account_id)
            ->where('client_id', $client->id)
            ->where('active', true)
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta bancaria no válida',
            ], 400);
        }

        // Procesar items y verificar stock
        $items = $request->items;
        $subtotal = 0;
        $orderItems = [];

        foreach ($items as $item) {
            $product = $client->products()->find($item['product_id']);

            if (!$product || $product->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => "El producto ya no está disponible",
                ], 400);
            }

            $variant = null;
            $price = $product->base_price;
            $variantName = null;

            // Si tiene variantes
            if ($product->has_variants && !empty($item['variant_id'])) {
                $variant = $product->variants()->find($item['variant_id']);
                if (!$variant || $variant->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para {$product->name}",
                    ], 400);
                }
                $price = $variant->getFinalPrice();
                $variantName = $variant->variant_name;
            } else {
                // Sin variantes
                if ($product->track_stock && $product->total_stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para {$product->name}",
                    ], 400);
                }
            }

            $itemSubtotal = $price * $item['quantity'];
            $subtotal += $itemSubtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'product_name' => $product->name,
                'variant_name' => $variantName,
                'sku' => $product->sku,
                'price' => $price,
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
            ];
        }

        // Crear la orden
        $order = Order::create([
            'client_id' => $client->id,
            'bank_account_id' => $request->bank_account_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city ?? null,
            'shipping_region' => $request->shipping_region ?? null,
            'shipping_zip' => $request->shipping_zip ?? null,
            'notes' => $request->notes ?? null,
            'subtotal' => $subtotal,
            'shipping_cost' => 0,
            'total_amount' => $subtotal,
            'status' => 'pending',
            'payment_method' => 'transfer',
            'payment_status' => 'pending',
            'expires_at' => now()->addHours(48),
        ]);

        // Crear items y reducir stock
        foreach ($orderItems as $index => $itemData) {
            $order->items()->create($itemData);

            // Reducir stock
            if ($itemData['variant_id']) {
                $variant = ProductVariant::find($itemData['variant_id']);
                $variant->stock -= $itemData['quantity'];
                $variant->save();
            } else {
                $product = Product::find($itemData['product_id']);
                $product->total_stock -= $itemData['quantity'];
                $product->save();
                $product->updateStatusBasedOnStock();
            }
        }

        // Preparar respuesta con datos del pago
        return response()->json([
            'success' => true,
            'message' => 'Orden creada correctamente',
            'data' => [
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->getOrderNumber(),
                    'status' => $order->status,
                    'total' => $order->total_amount,
                    'formatted_total' => $order->formatTotal(),
                    'created_at' => $order->created_at->toIso8601String(),
                    'expires_at' => $order->expires_at->toIso8601String(),
                ],
                'payment_info' => [
                    'method' => 'transferencia_bancaria',
                    'bank' => [
                        'name' => $bankAccount->bank_name,
                        'account_type' => $bankAccount->account_type,
                        'account_number' => $bankAccount->account_number,
                        'account_holder' => $bankAccount->account_holder,
                        'rut' => $bankAccount->rut,
                        'instructions' => $bankAccount->instructions,
                    ],
                ],
                'customer' => [
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                ],
            ],
        ], 201);
    }

    /**
     * Ver orden por ID y email (para consulta pública)
     */
    public function viewOrder(Request $request, $clientSlug, $orderId)
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->where('store_enabled', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = $client->orders()
            ->where('id', $orderId)
            ->where('customer_email', $request->email)
            ->with('items', 'bankAccount')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->getOrderNumber(),
                    'status' => $order->status,
                    'status_label' => $order->getStatusBadge()['label'],
                    'total' => $order->total_amount,
                    'formatted_total' => $order->formatTotal(),
                    'created_at' => $order->created_at->toIso8601String(),
                    'tracking_number' => $order->tracking_number,
                    'shipping_carrier' => $order->shipping_carrier,
                    'shipped_at' => $order->shipped_at?->toIso8601String(),
                    'delivered_at' => $order->delivered_at?->toIso8601String(),
                ],
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'formatted_price' => $item->formatPrice(),
                        'subtotal' => $item->subtotal,
                        'formatted_subtotal' => $item->formatSubtotal(),
                    ];
                }),
                'shipping' => [
                    'name' => $order->customer_name,
                    'address' => $order->shipping_address,
                    'city' => $order->shipping_city,
                    'region' => $order->shipping_region,
                    'zip' => $order->shipping_zip,
                ],
            ],
        ]);
    }

    /**
     * Subir comprobante de pago
     */
    public function uploadReceipt(Request $request, $clientSlug, $orderId)
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->where('store_enabled', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'transfer_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = $client->orders()
            ->where('id', $orderId)
            ->where('customer_email', $request->email)
            ->firstOrFail();

        if (!$order->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta orden ya no acepta comprobantes',
            ], 400);
        }

        // Guardar archivo
        $path = $request->file('receipt')->store('receipts/' . $client->id, 'public');

        $order->transfer_receipt_path = $path;
        $order->transfer_date = $request->transfer_date ?? now();
        $order->status = 'payment_review';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Comprobante subido correctamente',
            'data' => [
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'status_label' => $order->getStatusBadge()['label'],
                ],
            ],
        ]);
    }
}
