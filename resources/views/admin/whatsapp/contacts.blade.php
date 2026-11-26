@php
    $creatingContact = session('whatsapp_contact_form') === 'create';
    $editingContactId = (int) session('whatsapp_contact_edit_id');
@endphp

<div class="card klt-wa-contacts">
    <div class="card-header">
        <h4 class="card-title mb-1">Team profiles</h4>
        <p class="text-muted mb-0 small">Add each person with a photo, name, title, and WhatsApp number. People who share a section label are grouped on the card.</p>
    </div>
    <div class="card-body pt-0">
        <form method="post" action="{{ route('admin.whatsapp.contacts.store') }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label" for="whatsappContactSection">Section</label>
                <input id="whatsappContactSection" name="section" type="text" maxlength="40" class="form-control" value="{{ $creatingContact ? old('section') : '' }}" placeholder="Guides">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="whatsappContactName">Name</label>
                <input id="whatsappContactName" name="name" type="text" maxlength="80" class="form-control" value="{{ $creatingContact ? old('name') : '' }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="whatsappContactTitle">Title / position</label>
                <input id="whatsappContactTitle" name="title" type="text" maxlength="80" class="form-control" value="{{ $creatingContact ? old('title') : '' }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="whatsappContactPhone">WhatsApp number</label>
                <input id="whatsappContactPhone" name="phone_number" type="text" inputmode="tel" maxlength="32" class="form-control" value="{{ $creatingContact ? old('phone_number') : '' }}" placeholder="+255 749 719 998" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="whatsappContactPhoto">Profile photo</label>
                <input id="whatsappContactPhoto" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
                <div class="form-text">JPG, PNG, or WebP. Maximum 4 MB.</div>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Add profile</button>
            </div>
            @if ($creatingContact)
                @foreach (['section', 'name', 'title', 'phone_number', 'photo'] as $field)
                    @error($field)
                        <div class="col-12"><div class="invalid-feedback d-block">{{ $message }}</div></div>
                    @enderror
                @endforeach
            @endif
        </form>

        @forelse ($setting->contacts as $contact)
            @php $editingContact = $editingContactId === (int) $contact->id; @endphp
            <article class="klt-wa-contact">
                @if ($contact->photoUrl())
                    <img src="{{ $contact->photoUrl() }}" alt="">
                @else
                    <span class="klt-wa-avatar" aria-hidden="true">{{ $contact->initials() }}</span>
                @endif

                <form method="post" action="{{ route('admin.whatsapp.contacts.update', $contact) }}" enctype="multipart/form-data" class="row g-2">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label" for="whatsappContactSection{{ $contact->id }}">Section</label>
                        <input id="whatsappContactSection{{ $contact->id }}" name="section" type="text" maxlength="40" class="form-control" value="{{ $editingContact ? old('section', $contact->section) : $contact->section }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="whatsappContactName{{ $contact->id }}">Name</label>
                        <input id="whatsappContactName{{ $contact->id }}" name="name" type="text" maxlength="80" class="form-control" value="{{ $editingContact ? old('name', $contact->name) : $contact->name }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="whatsappContactTitle{{ $contact->id }}">Title / position</label>
                        <input id="whatsappContactTitle{{ $contact->id }}" name="title" type="text" maxlength="80" class="form-control" value="{{ $editingContact ? old('title', $contact->title) : $contact->title }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="whatsappContactPhone{{ $contact->id }}">WhatsApp number</label>
                        <input id="whatsappContactPhone{{ $contact->id }}" name="phone_number" type="text" inputmode="tel" maxlength="32" class="form-control" value="{{ $editingContact ? old('phone_number', $contact->phone_number) : $contact->phone_number }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappContactPhoto{{ $contact->id }}">Profile photo</label>
                        <input id="whatsappContactPhoto{{ $contact->id }}" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
                    </div>
                    @if ($contact->photoUrl())
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="whatsappContactRemovePhoto{{ $contact->id }}">
                                <label class="form-check-label" for="whatsappContactRemovePhoto{{ $contact->id }}">Remove photo</label>
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save profile</button>
                    </div>
                    @if ($editingContact)
                        @foreach (['section', 'name', 'title', 'phone_number', 'photo'] as $field)
                            @error($field)
                                <div class="col-12"><div class="invalid-feedback d-block">{{ $message }}</div></div>
                            @enderror
                        @endforeach
                    @endif
                </form>

                <form method="post" action="{{ route('admin.whatsapp.contacts.destroy', $contact) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Remove profile</button>
                </form>
            </article>
        @empty
            <p class="text-muted mb-0 mt-3">No profiles yet. The card uses the main number until you add someone.</p>
        @endforelse
    </div>
</div>
