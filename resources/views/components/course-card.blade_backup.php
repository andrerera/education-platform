@isset($course)
<div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
  <!-- Gambar -->
  <div class="relative">
    <img class="w-full h-48 object-cover" 
         src="{{ $course->thumbnail !== '-' ? asset('storage/' . $course->thumbnail) : asset('images/default.jpg') }}" 
         alt="{{ $course->title }}">
    
    @if(isset($enrolledCourseIds) && in_array($course->id, $enrolledCourseIds))
    <span class="absolute top-3 left-3 bg-green-600 text-white text-[10px] font-semibold px-2 py-1 rounded-full shadow">
    ✅ Diikuti
    </span>
    @endif
    <!-- Badge level -->
    <div class="absolute top-3 right-3">
      <span class="bg-white/90 backdrop-blur-sm text-xs font-semibold px-2 py-1 rounded-full text-gray-700">
        📘 {{ ucfirst($course->level ?? 'Beginner') }}
      </span>
    </div>
  </div>

  <!-- Konten -->
  <div class="px-6 py-4 flex-1 flex flex-col">
    <!-- Title -->
    <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2 leading-tight">
      {{ $course->title }}
    </h3>
    
    <!-- (Optional) Placeholder for Rating - dihapus atau diganti teks default -->
    {{-- <div class="flex items-center mb-3">
      <div class="flex items-center text-yellow-500 text-sm">
        <span class="mr-1">⭐</span>
        <span class="font-medium">4.5</span>
      </div>
    </div> --}}

    <!-- Description -->
    <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
      {{ $course->description }}
    </p>

    <!-- Info tags -->
    <div class="flex flex-wrap gap-2 text-xs text-gray-600 mb-4">
      {{-- Durasi (opsional) --}}
      @if(!empty($course->duration))
        <span class="bg-blue-50 text-blue-700 rounded-full px-3 py-1 font-medium border border-blue-200">
          ⏱️ {{ $course->duration }}
        </span>
      @endif

      <span class="bg-green-50 text-green-700 rounded-full px-3 py-1 font-medium border border-green-200">
        👨‍🎓 {{ $course->students_count ?? 0 }} Siswa
      </span>
    </div>

    <!-- Footer -->
    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
      <div class="text-lg font-bold text-gray-800">
        @if (($course->price ?? 0) == 0)
          <span class="text-green-600">Gratis</span>
          @if(isset($course->original_price) && $course->original_price > 0)
            <span class="text-sm text-gray-400 line-through ml-2">Rp {{ number_format($course->original_price) }}</span>
          @endif
        @else
          <span class="text-blue-600">Rp {{ number_format($course->price) }}</span>
        @endif
      </div>
      <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
        <span>Pelajari</span>
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </a>
    </div>
  </div>
</div>
@endisset