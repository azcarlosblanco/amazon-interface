<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ItemSearchRequest;
use App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract;

class AmazonSearchController extends Controller
{
    /**
     * Show item's search form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.search.index');
    }

    /**
     * Search items based on given requests parameters.
     *
     * @param  \App\Http\Requests\ItemSearchRequest  $request
     * @param  App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract  $amazonAPIManager
     * @param  Array $results
     * @return \Illuminate\Http\Response
     */
    public function itemSearch(ItemSearchRequest $request, AmazonAPIManagerContract $amazonAPIManager, array $results = array())
    {

        $results = $amazonAPIManager->itemSearch($request->toArray());

        $error = $this->multipleResultsErrorHandler($results);

        if (!is_null($error)) {
            return redirect()
                ->back()
                ->with('alert', $error['message']);
        }

        return view('home.search.results', compact('results'));
    }

    /**
     * MultipleResultsErrorHandler
     * @param Array $results
     */
    protected function multipleResultsErrorHandler($results)
    {
        if (isset($results[0]->error)) {

            return array(
                'error' => true,
                'message' => $results[0]->error->__toString(),
            );

        }

        foreach ($results as $result) {

            try {
                $this->amazonErrorhandler($result->Items->Request->IsValid->__toString(), $result);
            } catch (\ErrorException $e) {
                return array(
                    'error' => true,
                    'message' => 'Ocurrio un error en la conexión con Amazon',
                );
            }
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function browseNodeLookup(Request $request, AmazonAPIManagerContract $amazonAPIManager, array $results = array())
    {
        if ($request->exists('node') && !$request->exists('child')) {
            $result = $amazonAPIManager->browseNodeLookup($request->node);
            if (isset($result->error)) {
                return redirect()
                    ->back()
                    ->with('alert', $result->error);
            }
            $actualCategory = $result->BrowseNodes->BrowseNode->BrowseNodeId->__toString();
            $categoryChildren = $result->BrowseNodes->BrowseNode->Children->children();
            $results = $amazonAPIManager->itemSearch($request->toArray());
            $error = $this->multipleResultsErrorHandler($results);
            if (!is_null($error)) {
                return redirect()
                    ->back()
                    ->with('alert', $error['message']);
            }


        } elseif ($request->exists('node') && $request->exists('child')) {
            $result = $amazonAPIManager->browseNodeLookup($request->child);
            if (isset($result->error)) {
                return redirect()
                    ->back()
                    ->with('alert', $result->error);
            }
            $actualCategory = $result->BrowseNodes->BrowseNode->BrowseNodeId->__toString();
            if (isset($result->BrowseNodes->BrowseNode->Children)) {
                $categoryChildren = $result->BrowseNodes->BrowseNode->Children->children();
            } else {
                $categoryChildren = $result->BrowseNodes->BrowseNode->Ancestors->BrowseNode;
            }
            $results = $amazonAPIManager->itemSearch($request->toArray());
            $error = $this->multipleResultsErrorHandler($results);
            if (!is_null($error)) {
                return redirect()
                    ->back()
                    ->with('alert', $error['message']);
            }
        }
        return view('home.search.browse_node_lookup', compact('actualCategory', 'categoryChildren', 'results'));
    }

    /**
     * Amazon errors handler
     * @param String $isValid
     * @return \Illuminate\Http\Response
     */
    protected function amazonErrorhandler($isValid, $result)
    {
        if ($isValid == "False") {
            return array(
                'error' => true,
                'message' => 'Amazon devolvió el siguiente error en al busqueda: ' . $result->Items->Request->Errors->Error->Message->__toString(),
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
