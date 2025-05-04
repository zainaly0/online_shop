<?php

use App\Models\Category;


function getCategories(){
    return Category::with('subCategory')->orderBy('id', 'DESC')->where('showHome', 'Yes')->where('status', 1)->get();
}