<?php

namespace App\Http\Controllers;

use App\ListItem;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth',['except' => ['index','show','getListItems']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = ListItem::all();

        $response = [
            'message' => 'Show all Lists',
            'lists' => $lists
        ];

        return response()->json($response,200);
    }

    /**
     * Display Items of List
     *
     * @return \Illuminate\Http\Response
     */
    public function getListItems($id)
    {

        $list = ListItem::find($id);


        if (!$list) {

            return response()->json(['message' => 'List is not Found'], 404);
        }

        $items = $list->items->all();

        $response = [
            'message' => 'Items of List',
            'list' => $list,
            'items' => $items
        ];

        return response()->json($response, 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $title = $request->title;

        $list = new ListItem([
            'title' => $title,

        ]);

        if ($list->save()) {
            $message = [
                'message' => 'List is Created',
                'list' => $list
            ];

            return response()->json($message, 201);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /* public function show($id)
    {
        $list = ListItem::where('id', $id)->first();

        if ($list) {
            $response = [
                'message' => 'List ID' . $list->id,
                'list' => $list
            ];
        } else {
            return response()->json(['message' => 'This List is not Found'], 404);
        }

        return response()->json($response, 200);

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
        $this->validate($request,[
            'title' => 'required',
        ]);

        $title = $request->title;

        $list = ListItem::where('id',$id)->first();

        if (!$list){
            return response()->json(['message' => 'This List is not Found'], 404);
        }

        $list->title = $title;

        if (!$list->update()){
            return response()->json(['message' => 'Error'], 404);
        }

        $list->show_list = [
            'href' => 'api/list/'.$list->id,
            'method' => 'GET'
        ];

        $response = [
            'message' => 'List is Updated',
            'list' => $list
        ];

        return response()->json($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $list = ListItem::find($id);

        if (!$list) {

            return response()->json(['message' => 'Deletion Failed'], 404);
        }

        $response = [
            'message' => 'List is Deleted',
            'Create' => [
                'href' => 'api/list/create',
                'method' => 'POST',
                'params' => 'title'
            ]

        ];

        return response()->json($response, 200);

    }

}
