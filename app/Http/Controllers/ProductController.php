<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    
 
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
        ]);
    
        // Generate the acronym from the brand name
        $brandWords = explode(' ', $validatedData['brand']);
        $brandAcronym = '';
        
        foreach ($brandWords as $word) {
            $brandAcronym .= strtoupper(substr($word, 0, 1));
        }
    
        // Find the highest existing code number for this brand in the description
        $existingProducts = Product::where('brand', $validatedData['brand'])
            ->get();
        
        $maxCodeNumber = 0;
        foreach ($existingProducts as $product) {
            $description = $product->description;
    
            // Search for the pattern of the brand acronym followed by a number
            if (preg_match('/\b' . $brandAcronym . '(\d+)\b/', $description, $matches)) {
                $maxCodeNumber = max($maxCodeNumber, (int)$matches[1]);
            }
        }
    
        // Generate the new code
        $newCodeNumber = $maxCodeNumber + 1;
        $newCode = $brandAcronym . $newCodeNumber;
    
        // Append the generated code to the description
        $validatedData['description'] = isset($validatedData['description']) ? $validatedData['description'] . ' ' . $newCode : $newCode;
    
        // Store the validated product data in the database
        Product::create($validatedData);
    
        // Redirect the user back to the product index page
        return redirect()->back()->with('success', 'Product added successfully.');
    }
    
    
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id, $variant_id)
{
    // Find the product by its ID
    $product = Product::findOrFail($id);

    // Find the variant by its ID
    $variant = ProductVariant::findOrFail($variant_id);

    // Load the image associated with this variant
    $images = $variant->images;

    // Pass the product, variant, and its associated image to the view
    $products = Product::orderBy('id', 'desc')->get();


    $categories = Product::distinct()->pluck('category'); // Get distinct categories

    return view('admin.products', [
        'products' => $products,
        'images' => $images,
        'product' => $product,
        'variant_id' => $variant_id,
        'categories' => $categories
    ]);
}
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $variant_id)
{
    $categories = Product::distinct()->pluck('category'); // Get distinct categories
    $products = Product::orderBy('id', 'desc')->get();
    $variant = ProductVariant::findOrFail($variant_id);
    return view('admin.products', ['products' => $products, 'item' => $variant, 'categories' => $categories]);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Find the item by its ID
    $item = ProductVariant::findOrFail($id);

    // Update the item's attributes with the values from the request
    $item->color = $request->input('color');
    $item->size = $request->input('size');
    $item->price = $request->input('price');

    // Save the changes to the item
    $item->save();

   
 
        $product = $item->product;
        

        // Update the product details
        $product->name = $request->input('name');
        $product->brand = $request->input('brand');
        $product->description = $request->input('description');

        // Save the changes to the product
        $product->save();
    

    // Redirect back to the profile page or wherever you need
    return redirect()->route('product')->with('success', 'Item updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Get all variants associated with the product
    $variants = $product->variants;

    // Loop through each variant
    foreach ($variants as $variant) {
        // Get all images associated with the variant
        $images = $variant->images;

        // Delete each image from storage
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // Delete all associated images from the database
        $variant->images()->delete();
    }

    // Delete all variants associated with the product
    $product->variants()->delete();

    // Delete the product
    $product->delete();

    return redirect()->back()->with('success', 'Product, variants, and associated images deleted successfully.');
}


public function addImage(Request $request)
{   
    // Validate the incoming request data
    $validatedData = $request->validate([
        'new_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Allow multiple images
    ]);

    // Retrieve the variant ID from the request
    $variant_id = $request->input('variant_id');

    // Find the product variant by its ID
    $variant = ProductVariant::findOrFail($variant_id);

    // Upload and store each new image
    foreach ($request->file('new_images') as $image) {
        $imageName = time() . '-' . uniqid() . '.webp';
        $webpImage = Image::read($image);
        $webpImage->save(public_path('storage/product_images/' . $imageName));
        $variant->images()->create(['path' => 'product_images/' . $imageName]);
   
    }



    return redirect()->back()->with('success', 'New images added successfully.');
}



public function deleteImage(ProductImage $image)
{
    // Delete the image from storage
    Storage::disk('public')->delete($image->path);

    // Delete the image record from the database
    $image->delete();

    // Redirect back with a success message or perform any other action
    return redirect()->back()->with('success', 'Image deleted successfully.');
}


public function search(Request $request)
{
    $query = $request->query('query');

    // Check if the query is empty or less than 2 characters
    if (empty($query) || strlen($query) < 2) {
        // Return all products with their associated variants and images where quantity is greater than 0, ordered by ID in descending order
        $searchedProducts = Product::with(['variants' => function ($query) {
            $query->where('quantity', '>', 0)->with('images');
        }])
        ->whereHas('variants', function ($variantQuery) {
            $variantQuery->where('quantity', '>', 0);
        })
        ->orderBy('id', 'desc') // Order by ID in descending order
        ->get();
    } else {
        // Perform the search based on the request, filter by quantity, and eager load the 'variants' relationship along with images, ordered by ID in descending order
        $searchedProducts = Product::with(['variants' => function ($variantQuery) {
            $variantQuery->where('quantity', '>', 0)->with('images');
        }])
        ->whereHas('variants', function ($variantQuery) use ($query) {
            $variantQuery->where('quantity', '>', 0)
                  ->where(function ($innerQuery) use ($query) {
                      $innerQuery->where('description', 'like', '%' . $query . '%')
                                   ->orWhere('name', 'like', '%' . $query . '%')
                                   ->orWhere('brand', 'like', '%' . $query . '%')
                                   ->orWhere('color', 'like', '%' . $query . '%')
                                   ->orWhere('size', 'like', '%' . $query . '%');
                  });
        })
        ->orderBy('id', 'desc') // Order by ID in descending order
        ->get();
    }

    // Return the search results as JSON
    return response()->json($searchedProducts);
}



public function updateStock(Request $request, $id, $variant_id)
{
    // Retrieve the product
    $products = Product::orderBy('id', 'desc')->get();;
    $product = Product::findOrFail($id);
    
    // Retrieve the specific variant using the variant ID
    $variant = $product->variants()->findOrFail($variant_id);

    // Pass the variant as the $item variable to the view
    return view('admin.inventory', ['products' => $products, 'item' => $variant]);
}


public function markAsSold($id, $variant_id)
{
    // Retrieve all products
    $products = Product::orderBy('id', 'desc')->get();;
    
    // Retrieve the specific product variant using the variant ID
    $variant = ProductVariant::findOrFail($variant_id);

    // Pass the variant as the $sold variable to the view
    return view('admin.inventory', ['products' => $products, 'sold' => $variant]);
}


public function sold(Request $request, $productId, $variantId)
{
    // Find the product and its variant
    $product = Product::findOrFail($productId);
    $variant = $product->variants()->findOrFail($variantId);

    // Validate the request
    $request->validate([
        'deductQuantity' => 'required|integer|min:1|max:'.$variant->quantity, // Make sure the deducted quantity is within the available stock of the variant
    ]);

    // Check if the deducted quantity is greater than the current stock
    if ($request->deductQuantity > $variant->quantity) {
        return redirect()->route('inventory')->with('error', 'Not enough stock to deduct');
    }

    // Deduct the quantity from the available stock of the variant
    $variant->quantity -= $request->deductQuantity;
    $variant->save();

    // Deduct the quantity from the available stock of the product
    $product->quantity -= $request->deductQuantity;
    $product->save();

    return redirect()->route('inventory')->with('success', 'Stock deducted successfully');
}




public function addStock(Request $request, $id)
{
    // Find the product variant
    $productVariant = ProductVariant::findOrFail($id);
    
    // Validate the request
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    // Update the product variant's quantity
    $productVariant->quantity += $request->quantity;
    $productVariant->save();

    // Update the corresponding product's quantity
    $productVariant->product->quantity += $request->quantity;
    $productVariant->product->save();

    // Redirect back with a success message
    return redirect()->route('inventory')->with('success', 'Stock added successfully!');
}


public function addvariant(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'price' => 'required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $variant = ProductVariant::create($validatedData);

        // Upload and store the images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Create a unique name for the image
                $imageName = time() . '-' . uniqid() . '.webp';

                // Convert and save the image as WebP
                $webpImage = Image::read($image);
                $webpImage->save(public_path('storage/product_images/' . $imageName));

                // Save the image path to the database
                $variant->images()->create(['path' => 'product_images/' . $imageName]);
            }
        }

        // Optionally, you can redirect back with a success message
        return redirect()->back()->with('success', 'Variant added successfully.');
    }

public function filter(Request $request)
{
    $category = $request->get('category');

    if (empty($category)) {
        // Return all products with their associated variants and images where quantity is greater than 0, ordered by ID in descending order
        $filteredProducts = Product::with(['variants' => function ($query) {
            $query->where('quantity', '>', 0)->with('images');
        }])
        ->whereHas('variants', function ($variantQuery) {
            $variantQuery->where('quantity', '>', 0);
        })
        ->orderBy('id', 'desc') // Order by ID in descending order
        ->get();
    } else {
        // Perform the filtering based on the exact category (description), filter by quantity, and eager load the 'variants' relationship along with images, ordered by ID in descending order
        $filteredProducts = Product::with(['variants' => function ($variantQuery) {
            $variantQuery->where('quantity', '>', 0)->with('images');
        }])
        ->whereRaw('category REGEXP ?', ['\\b' . $category . '\\b'])
        ->whereHas('variants', function ($variantQuery) {
            $variantQuery->where('quantity', '>', 0);
        })
        ->orderBy('id', 'desc') // Order by ID in descending order
        ->get();
    }

    // Return the filtered results as JSON
    return response()->json($filteredProducts);
}




}
