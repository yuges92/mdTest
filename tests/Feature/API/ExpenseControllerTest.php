<?php

namespace Tests\Feature\API;

use App\Models\Expense;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{

    private $tableName="expenses";
    /**
     * testing for listing all the expenses
     * @test
     */
    public function canListExpenses()
    {
        $count = 10;
        $listOfExpenses = Expense::factory()->count($count)->create();
        $response = $this->getJson(route('expenses.index'));
        $response->assertSuccessful();
        $responseDecoded = $response->decodeResponseJson();
        $this->assertEquals($count, $responseDecoded->count());
        $this->assertDatabaseCount($this->tableName, $count);
        $this->assertDatabaseHas($this->tableName, $listOfExpenses->first()->only(['description', 'value']));

    }

    /**
     * Test for creating a new expenses
     * @test
     */
    public function canCreateANewExpense()
    {

        $expenseData = Expense::factory()->make()->toArray();
        $response = $this->postJson(route('expenses.store'), $expenseData);
        $response->assertSuccessful();
        $this->assertDatabaseHas($this->tableName, $expenseData);

    }

    /**
     * Test for retrieving an expenses
     * @test
     */
    public function canGetAExpense()
    {
        $expense = Expense::factory()->create();
        $response = $this->getJson(route('expenses.show', $expense->id));
        $response->assertSuccessful();
        $responseDecoded = $response->decodeResponseJson();
        $response->assertJson($expense->toArray());
        $this->assertDatabaseCount($this->tableName, 1);
        $this->assertDatabaseHas($this->tableName, $expense->only(['description', 'value']));
        $this->assertEquals($expense->id, $responseDecoded['id']);
        $this->assertEquals($expense->description, $responseDecoded['description']);
        $this->assertEquals($expense->value, $responseDecoded['value']);
    }

    /**
     * Test for updating an existing expenses
     * @test
     */
    public function canUpdateAExpense()
    {
        $expense = Expense::factory()->create();
        $dataToUpdate = Expense::factory()->make()->toArray();
        $response = $this->putJson(route('expenses.update', $expense->id), $dataToUpdate);
        $response->assertSuccessful();
        $responseDecoded = $response->decodeResponseJson();
        $this->assertDatabaseCount($this->tableName, 1);
        $expenseUpdated = Expense::findOrFail($expense->id);
        $this->assertDatabaseHas($this->tableName, $expenseUpdated->only(['description', 'value']));
        $response->assertJson($expenseUpdated->toArray());
        $this->assertEquals($expenseUpdated->id, $responseDecoded['id']);
        $this->assertEquals($expenseUpdated->description, $responseDecoded['description']);
        $this->assertEquals($expenseUpdated->value, $responseDecoded['value']);
    }


    /**
     * Test for deleting an expenses
     * @test
     */
    public function canDeleteAExpense()
    {
        $expense = Expense::factory()->create();

        $response = $this->deleteJson(route('expenses.destroy', $expense->id));
        $response->assertSuccessful();
        $this->assertDatabaseCount($this->tableName, 0);
        $this->assertDatabaseMissing($this->tableName, $expense->only(['description', 'value']));
    }
}
