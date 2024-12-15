<section class="space-y-6">
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-artikel')"
    >{{ __('Delete Artikel') }}</x-danger-button>
    <x-modal name="confirm-delete-artikel" focusable>
        <form method="post" action="{{ route('artikel.destroy', $artikel) }}" class="p-6">
            @csrf
            @method('DELETE')
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('apa kamu yakin delete artikel?') }}
            </h2>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-danger-button class="ms-3">
                    {{ __('Delete Artikel') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>