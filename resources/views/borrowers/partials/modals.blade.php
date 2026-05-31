{{-- CREATE MEMBER MODAL --}}
<div x-show="openModal"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
     style="display:none;">

    <div @click.away="openModal = false"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-6 overflow-y-auto max-h-[95vh]">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    New Member Registration
                </h2>

                <p class="text-sm text-gray-500">
                    Add a new chama member into the system
                </p>
            </div>

            <button @click="openModal = false"
                    class="text-gray-400 hover:text-red-500 text-xl">
                ✕
            </button>
        </div>

        <form method="POST"
              action="{{ route('borrowers.store') }}"
              class="space-y-6">

            @csrf

            {{-- PROFILE ICON --}}
            <div class="flex justify-center">
                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-3xl font-bold text-blue-600">
                    👤
                </div>
            </div>

            {{-- PERSONAL INFO --}}
            <div>

                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                    Personal Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="text-sm text-gray-600">
                            First Name
                        </label>

                        <input type="text"
                               name="firstname"
                               required
                               class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                               placeholder="John">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Middle Name
                        </label>

                        <input type="text"
                               name="middlename"
                               class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                               placeholder="Optional">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Last Name
                        </label>

                        <input type="text"
                               name="lastname"
                               required
                               class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                               placeholder="Doe">
                    </div>

                </div>

            </div>

            {{-- CONTACT INFO --}}
            <div>

                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                    Contact Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-gray-600">
                            Phone Number
                        </label>

                        <input type="text"
                               name="contact_no"
                               required
                               class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                               placeholder="07XXXXXXXX">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Email Address
                        </label>

                        <input type="email"
                               name="email"
                               class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                               placeholder="member@email.com">
                    </div>

                </div>

            </div>

            {{-- MEMBERSHIP DETAILS --}}
            <div>

                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                    Membership Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm text-gray-600">
                            National ID / Tax ID
                        </label>

                        <input type="text"
                               name="tax_id"
                               class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                               placeholder="Optional">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Member Status
                        </label>

                        <select name="status"
                                class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>
                    </div>

                </div>

                <div class="mt-4">

                    <label class="text-sm text-gray-600">
                        Address
                    </label>

                    <textarea name="address"
                              rows="3"
                              class="w-full border rounded-xl p-3 mt-1 focus:ring-2 focus:ring-blue-500"
                              placeholder="Member address"></textarea>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <button type="button"
                        @click="openModal = false"
                        class="px-5 py-2 rounded-xl border hover:bg-gray-100">

                    Cancel

                </button>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl shadow">

                    Save Member

                </button>

            </div>

        </form>

    </div>
</div>


{{-- EDIT MEMBER MODAL --}}
<div x-show="openEditModal"
     class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
     style="display:none;">

    <div @click.away="openEditModal = false"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-6 overflow-y-auto max-h-[95vh]">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Edit Member
                </h2>

                <p class="text-sm text-gray-500">
                    Update member information
                </p>
            </div>

            <button @click="openEditModal = false"
                    class="text-gray-400 hover:text-red-500 text-xl">
                ✕
            </button>

        </div>

        <form :action="'/borrowers/' + editBorrower.id"
              method="POST"
              class="space-y-6">

            @csrf
            @method('PUT')

            {{-- PROFILE ICON --}}
            <div class="flex justify-center">
                <div class="h-20 w-20 rounded-full bg-yellow-100 flex items-center justify-center text-3xl font-bold text-yellow-600">
                    ✏️
                </div>
            </div>

            {{-- PERSONAL INFO --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                    Personal Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <input type="text"
                           name="firstname"
                           x-model="editBorrower.firstname"
                           class="w-full border rounded-xl p-3"
                           placeholder="First Name">

                    <input type="text"
                           name="middlename"
                           x-model="editBorrower.middlename"
                           class="w-full border rounded-xl p-3"
                           placeholder="Middle Name">

                    <input type="text"
                           name="lastname"
                           x-model="editBorrower.lastname"
                           class="w-full border rounded-xl p-3"
                           placeholder="Last Name">

                </div>
            </div>

            {{-- CONTACT --}}
            <div>

                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                    Contact Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <input type="text"
                           name="contact_no"
                           x-model="editBorrower.contact_no"
                           class="w-full border rounded-xl p-3"
                           placeholder="Phone">

                    <input type="email"
                           name="email"
                           x-model="editBorrower.email"
                           class="w-full border rounded-xl p-3"
                           placeholder="Email">

                </div>

            </div>

            {{-- MEMBERSHIP DETAILS --}}
            <div>

                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-4">
                    Membership Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <input type="text"
                           name="tax_id"
                           x-model="editBorrower.tax_id"
                           class="w-full border rounded-xl p-3"
                           placeholder="Tax / ID Number">

                    <input type="text"
                           name="address"
                           x-model="editBorrower.address"
                           class="w-full border rounded-xl p-3"
                           placeholder="Address">

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <button type="button"
                        @click="openEditModal = false"
                        class="px-5 py-2 border rounded-xl hover:bg-gray-100">

                    Cancel

                </button>

                <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-xl shadow">

                    Update Member

                </button>

            </div>

        </form>

    </div>
</div>


{{-- VIEW MODAL --}}
<div x-show="openViewModal"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div @click.away="openViewModal = false"
         class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">

        <h2 class="text-xl font-bold mb-4">Member Details</h2>

        <div class="space-y-2 text-sm">

            <div><b>Name:</b> <span x-text="viewBorrower.firstname + ' ' + viewBorrower.lastname"></span></div>
            <div><b>Email:</b> <span x-text="viewBorrower.email"></span></div>
            <div><b>Phone:</b> <span x-text="viewBorrower.contact_no"></span></div>
            <div><b>Address:</b> <span x-text="viewBorrower.address"></span></div>

        </div>

        <div class="mt-4 text-right">
            <button @click="openViewModal = false"
                    class="px-4 py-2 border rounded">
                Close
            </button>
        </div>

    </div>
</div>
