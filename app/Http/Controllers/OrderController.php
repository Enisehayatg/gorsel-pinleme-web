<?php

namespace App\Http\Controllers;

use App\Models\Order;

// example
class OrderController
{
    use Controller;

    public function __construct()
    {
        $this->model = Order::class;
        $this->controllerName = 'admin.order';

        // $this->compact['users'] = User::pluck('name', 'id')->toArray();
        // $this->compact['books'] = Book::pluck('title', 'id')->toArray();

        $this->validationRules = [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,completed,cancelled',
        ];

        $this->dataTableColumns = [
            'user_name' => function ($item) {
                return $item->user ? $item->user->name : '-';
            },
            'book_title' => function ($item) {
                return $item->book ? $item->book->title : '-';
            },
            'quantity' => function ($item) {
                return $item->quantity;
            },
            'total_price' => function ($item) {
                return number_format($item->total_price, 2);
            },
            'status' => function ($item) {
                return ucfirst($item->status);
            },
            'created_at' => function ($item) {
                return $item->created_at ? $item->created_at->format('d-m-Y H:i:s') : '-';
            },
        ];
    }
}
