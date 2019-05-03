namespace {{ $namespace }};

@if(ends_with($baseController,'\\Controller'))
use {{ $baseController }};
@else
use {{ $baseController }} as Controller;
@endif
use Illuminate\Http\Request;
use {{ $modelClass }};

class {{ $controllerName }} extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
@if ($protected)
        if (!$request->user()->can('{{ $readPermission }}')) {
            flash("You can't read a {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        {{ $objects }} = {{ $model }}::paginate();
        return view('{{ $viewPath }}.index', ['{{ $items }}' => {{ $objects }}]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
@if ($protected)
        if (!$request->user()->can('{{ $createPermission }}')) {
            flash("You can't create a {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        return view('{{ $viewPath }}.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
@if ($protected)
        if (!$request->user()->can('{{ $createPermission }}')) {
            flash("You can't create a {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        $rules = [
            // Insert here your validation rules!
        ];

        $data = $request->validate($rules);

        // Prepare $data for the creation of the resource

        {{ $object }} = {{ $model }}::create($data);

        flash('{{ $model }} created succesfully')->success();
        return redirect()->route('{{ $indexRoute }}');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $model }} {{ $object }}
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, {{ $model }} {{ $object }})
    {
@if ($protected)
@if ($owned)
        if (!$request->user()->can('{{ $readPermission }}') || !$request->user()->owns($object)) {
@else
        if (!$request->user()->can('{{ $readPermission }}')) {
@endif
            flash("You can't read a {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        return view('{{ $viewPath }}.show', ['{{ $item }}' => {{ $object }}]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $model }} {{ $object }}
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, {{ $model }} {{ $object }})
    {
@if ($protected)
@if ($owned)
        if (!$request->user()->can('{{ $updatePermission }}') || !$request->user()->owns($object)) {
@else
        if (!$request->user()->can('{{ $updatePermission }}')) {
@endif
            flash("You can't update this {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        return view('{{ $viewPath }}.edit', ['{{ $item }}' => {{ $object }}]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  {{ $model }} {{ $object }}
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, {{ $model }} {{ $object }})
    {
@if ($protected)
@if ($owned)
        if (!$request->user()->can('{{ $updatePermission }}') || !$request->user()->owns($object)) {
@else
        if (!$request->user()->can('{{ $updatePermission }}')) {
@endif
            flash("You can't update this {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        $rules = [
            // Insert here your validation rules!
        ];

        $data = $request->validate($rules);

        {{ $object }}->update($data);

        flash('{{ $model }} created updated')->success();
        return redirect()->route('{{ $indexRoute }}');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  {{ $model }} {{ $object }}
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, {{ $model }} {{ $object }})
    {
@if ($protected)
@if ($owned)
        if (!$request->user()->can('{{ $deletePermission }}') || !$request->user()->owns($object)) {
@else
        if (!$request->user()->can('{{ $deletePermission }}')) {
@endif
            flash("You can't delete this {{ $name }}")->error();
            return redirect()->route('{{ $indexRoute }}');
        }

@endif
        {{ $object }}->delete();

        flash('{{ $model }} deleted')->success();
        return redirect()->route('{{ $indexRoute }}');
    }
}
