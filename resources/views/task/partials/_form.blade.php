<div class="mt-3 mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" name="name" id="name" value="@if(!empty($task)) {{ $task->name }} @endif">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="text" class="form-control" name="email" id="email" value="@if(!empty($task)) {{ $task->email }} @endif">
  </div>
  <div class="mb-3">
    <label for="contact_number" class="form-label">Contact number</label>
    <input type="number" class="form-control" name="contact_number" id="contact_number" value="{{ isset($task) ? strval($task->contact_number) : '' }}">
</div>
  <div class="mb-3">
    <label for="gender" class="form-label">Gender</label>
    <select class="form-select" name="gender" id="gender">
      <option value="male" @if(!empty($task) && $task->gender == 'male') selected @endif>Male</option>
    <option value="female" @if(!empty($task) && $task->gender == 'female') selected @endif>Female</option>
    <option value="other" @if(!empty($task) && $task->gender == 'other') selected @endif>Other</option>
    </select>
  </div>
  <div class="mb-3">
    <label for="profile_pic" class="form-label">Profile picture</label>
    <input type="file" id="profile_pic" name="profile_pic" class="form-control">
  </div>
  <div class="mb-3">
    <label for="hobbies" class="form-label">Hobbies</label>
    <select name="hobbies[]" id="hobbies" class="form-control select2" multiple="multiple" data-placeholder="--Select hobby--">
      @foreach($hobbies as $hobby)
      @php
          $selected = (!empty($task) && $task->hobbies->pluck('name')->contains($hobby)) ? 'selected' : '';
      @endphp
    <option value="{{ $hobby }}" {{ $selected }}>{{ $hobby }}</option>

      @endforeach
  </select>  
  </div>   
  @if($states->isEmpty()==true)
      <div class="alert alert-danger" role="alert">
        Please run the migration first for state
      </div>
      @else
  <div class="mb-3">
    <label for="state" class="form-label">State</label>
    <select name="state" id="state" class="form-control">
      <option>--Select State--</option>      
      @foreach($states as $state)      
      @php    
          $selected = (!empty($task) && $task->state->name === $state->name) ? 'selected' : '';
      @endphp
      <option value="{{ $state->id }}" {{ $selected }}>{{ $state->name }}</option>
      @endforeach
    </select>
  </div>
  @endif
  <div class="mb-3">
    <label for="city" class="form-label">City</label>
    <select name="city" id="city" class="form-control">
    </select>
  </div>
  @if (isset($task) && !empty($task->profile_pic))
    <div class="mb-3">
        <label for="profile_pic_preview" class="form-label">Current Profile picture</label>
        <img src="{{ asset('storage/') }}/{{ $task->profile_pic }}" class="img-fluid" width="150" height="150" alt="Profile Picture">
    </div>
@endif