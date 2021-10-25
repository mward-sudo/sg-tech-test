<x-layout>
    <x-slot name="title">Homeowner names</x-slot>

    <h1 class="text-2xl font-bold mb-6 mt-4">Homeowner Names</h1>

    <table class="border-collapse table-auto -ml-10">
        <thead>
            <tr class="bg-black">
                <th class="bg-white"></th>
                <th class="border-2 border-solid border-gray-500 py-1 pl-2 pr-8 text-white font-normal text-left">Title</th>
                <th class="border-2 border-solid border-gray-500 py-1 pl-2 pr-8 text-white font-normal text-left">First Name</th>
                <th class="border-2 border-solid border-gray-500 py-1 pl-2 pr-8 text-white font-normal text-left">Initial</th>
                <th class="border-2 border-solid border-gray-500 py-1 pl-2 pr-8 text-white font-normal text-left">Last Name</th>
            </tr>
        </thead>
        <tbody>
        @foreach($homeOwners as $homeOwner)
            <tr class="{{ $loop->even ? 'bg-gray-100' : '' }}">
                <td class="text-xs w-10 text-left text-gray-400 bg-white">#{{$loop->index + 1}}</td>
                <td class="border-2 border-solid border-gray-200 py-1 pl-2 pr-8">{{$homeOwner->title}}</td>
                <td class="border-2 border-solid border-gray-200 py-1 pl-2 pr-8">{{$homeOwner->first_name}}</td>
                <td class="border-2 border-solid border-gray-200 py-1 pl-2 pr-8">{{$homeOwner->initial}}</td>
                <td class="border-2 border-solid border-gray-200 py-1 pl-2 pr-8">{{$homeOwner->last_name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-layout>
