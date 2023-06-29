<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CategoryManagement extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(private readonly Category $category)
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category-management', [
            'categories' => $this->category->withCount(['articles'])->get()
        ]);
    }
}
