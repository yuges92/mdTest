<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseStoreRequest;
use App\Models\Expense;
use Exception;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    /**
     * Return the list of expenses.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Expense::all());
    }

    /**
     * Store a newly created expense after validating.
     *
     * @param ExpenseStoreRequest $request
     * @return JsonResponse
     */
    public function store(ExpenseStoreRequest $request): JsonResponse
    {
        $expense = Expense::create($request->validated());
        return response()->json($expense, 201);
    }

    /**
     * Return the expense data for the requested expense.
     *
     * @param Expense $expense
     * @return JsonResponse
     */
    public function show(Expense $expense): JsonResponse
    {
        return response()->json($expense);

    }

    /**
     * Update the specified expense and return the updated expense.
     *
     * @param ExpenseStoreRequest $request
     * @param Expense $expense
     * @return JsonResponse
     */
    public function update(ExpenseStoreRequest $request, Expense $expense): JsonResponse
    {
        $expense->update($request->validated());

        return response()->json($expense, 201);
    }

    /**
     * Remove the specified expense.
     *
     * @param Expense $expense
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Expense $expense): JsonResponse
    {
        try {
            $expense->delete();
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
        return response()->json(204);
    }

}
