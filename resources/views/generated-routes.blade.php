@if ($protected)
Route::middleware('auth')->group(function () {
    Route::get('{{ $url }}', '{{ $controller }}@@index')->name('{{ $route }}.index');
    Route::get('{{ $url }}/create', '{{ $controller }}@@create')->name('{{ $route }}.create');
    Route::post('{{ $url }}', '{{ $controller }}@@store')->name('{{ $route }}.store');
    Route::get('{{ $url }}/{invite}', '{{ $controller }}@@show')->name('{{ $route }}.show');
    Route::get('{{ $url }}/{invite}/edit', '{{ $controller }}@@edit')->name('{{ $route }}.edit');
    Route::patch('{{ $url }}/{invite}', '{{ $controller }}@@update')->name('{{ $route }}.update');
    Route::delete('{{ $url }}/{invite}', '{{ $controller }}@@delete')->name('{{ $route }}.delete');
});
@else
Route::get('{{ $url }}', '{{ $controller }}@@index')->name('{{ $route }}.index');
Route::get('{{ $url }}/create', '{{ $controller }}@@create')->name('{{ $route }}.create');
Route::post('{{ $url }}', '{{ $controller }}@@store')->name('{{ $route }}.store');
Route::get('{{ $url }}/{invite}', '{{ $controller }}@@show')->name('{{ $route }}.show');
Route::get('{{ $url }}/{invite}/edit', '{{ $controller }}@@edit')->name('{{ $route }}.edit');
Route::patch('{{ $url }}/{invite}', '{{ $controller }}@@update')->name('{{ $route }}.update');
Route::delete('{{ $url }}/{invite}', '{{ $controller }}@@delete')->name('{{ $route }}.delete');
@endif