<?php

namespace App\Http\Controllers;

use App\Item;
use App\ListItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function store(Request $request,$id)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $list = ListItem::find($id);

        $list_id = $list->id;

        $title = $request->title;

        $item = new Item([
            'title' => $title,
            'list_id' => $list_id

        ]);

        if ($item->save()) {
            $message = [
                'message' => 'Item is Created',
                'item' => $item,
                'list' => $list
            ];

            return response()->json($message, 201);
        }
    }

    /**
     * Update Item in List.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id , int $list_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$list_id,$id)
    {
        $this->validate($request,[
            'title' => 'required',
        ]);

        $title = $request->title;

        $item = Item::find($id);

        $list = ListItem::where('id',$list_id)->first();

        if (!$list){
            return response()->json(['message' => 'List is not Found in the Database'], 404);
        }
        elseif (!$list->items()->where('items.id',$id)->first()){
            return response()->json(['message' => 'Item is not Found in this list'], 404);
        }
        $item->title = $title;

        if (!$item->update()){
            return response()->json(['message' => 'Error'], 404);
        }

        $response = [
            'message' => 'Item is Updated',
            'item' => $item
        ];

        return response()->json($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($list_id,$id)
    {
        $item = Item::find($id);

        $list = ListItem::where('id',$list_id)->first();


        if (!$list){
            return response()->json(['message' => 'List is not Found in the Database'], 404);
        }
        elseif (!$list->items()->where('items.id',$id)->first()){
            return response()->json(['message' => 'Item is not Found in this list'], 404);
        }

        $response = [
            'message' => 'Item '.$item->title. ' is Deleted',
            'Create' => [
                'href' => 'api/list/'.$list->id.'/item',
                'method' => 'POST',
                'params' => 'title'
            ]

        ];

        return response()->json($response, 200);
    }
}
