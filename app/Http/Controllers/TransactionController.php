<?php

namespace App\Http\Controllers;


use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $transactions = Transaction::orderBy('time', 'DESC')->get();
    $response = [
      'message' => 'List of all transactions by time',
      'data' => $transactions
    ];

    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title'   => ['required', 'string', 'max:255'],
      'amount'  => ['required', 'numeric'],
      'type'    => ['required', 'in:income,expense']
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    Try {
      $transaction = Transaction::create($request->all());
      $response = [
        'message' => 'Transaction created successfully',
        'data' => $transaction
      ];

      return response()->json($response, Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->errorInfo], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $transaction = Transaction::find($id);

    if (!$transaction) {
      return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
    }

    $response = [
      'message' => 'Transaction found',
      'data' => $transaction
    ];

    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $findTransaction = Transaction::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'title'   => ['required', 'string', 'max:255'],
      'amount'  => ['required', 'numeric'],
      'type'    => ['required', 'in:income,expense']
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    Try {
      $findTransaction->update($request->all());
      $response = [
        'message' => 'Transaction update successfully',
        'data' => $findTransaction
      ];

      return response()->json($response, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->errorInfo], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $findTransaction = Transaction::findOrFail($id);

    if (!$findTransaction) {
      return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
    }

    Try {
      $findTransaction->delete();
      $response = [
        'message' => 'Transaction deleted successfully'
      ];

      return response()->json($response, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->errorInfo], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
