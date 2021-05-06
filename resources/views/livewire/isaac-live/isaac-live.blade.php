<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <form name="index" method="post" action="{{url('admin/isaac/formula')}}">
    
    @csrf
    Formula: <input type="text" name="formula" >
    
    <input type="submit" value="agregar" />
    
    </form>
</div>
