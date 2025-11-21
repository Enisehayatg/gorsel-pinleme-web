<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Görevler - Görsel Pinle</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      touch-action: manipulation;
    }
    .task-done {
      text-decoration: line-through;
      color: #9ca3af;
      transition: all 0.3s ease;
      opacity: 0.7;
    }
    .task-text {
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .date-scroll::-webkit-scrollbar {
      display: none;
    }
    .date-scroll {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .task-item:hover .task-actions {
      opacity: 1;
    }
    .task-actions {
      opacity: 0;
      transform: translateX(10px);
      transition: all 0.2s ease-in-out;
      background: white;
      border-radius: 8px;
      padding: 4px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .task-item:hover .task-actions {
      opacity: 1;
      transform: translateX(0);
    }
    .task-action-btn {
      padding: 8px;
      border-radius: 6px;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .task-action-btn svg {
      width: 18px;
      height: 18px;
    }
    .task-action-btn.photo:hover {
      background-color: #ecfdf5;
      color: #059669;
    }
    .task-action-btn.edit:hover {
      background-color: #eff6ff;
      color: #3b82f6;
    }
    .task-action-btn.delete:hover {
      background-color: #fef2f2;
      color: #ef4444;
    }
    @media (max-width: 768px) {
      .desktop-nav {
        display: none;
      }
      .task-actions {
        opacity: 1;
        transform: none;
        background: transparent;
        box-shadow: none;
      }
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .custom-checkbox {
      appearance: none;
      width: 22px;
      height: 22px;
      border: 2px solid #e5e7eb;
      border-radius: 999px;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      background: white;
    }
    
    .custom-checkbox:checked {
      background-color: #ec4899;
      border-color: #ec4899;
      transform: scale(1.05);
    }
    
    .custom-checkbox:checked::after {
      content: '';
      position: absolute;
      left: 7px;
      top: 3px;
      width: 6px;
      height: 10px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
      opacity: 0;
      animation: checkmark 0.2s ease forwards;
    }

    @keyframes checkmark {
      from {
        opacity: 0;
        transform: rotate(45deg) scale(0.8);
      }
      to {
        opacity: 1;
        transform: rotate(45deg) scale(1);
      }
    }
    
    .custom-checkbox:hover {
      border-color: #ec4899;
      transform: scale(1.05);
    }

    .task-item {
      transition: all 0.3s ease;
      border: 1px solid transparent;
    }

    .task-item:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      border-color: #ec4899;
    }

    .task-image {
      width: 100px;
      height: 100px;
      cursor: pointer;
      transition: all 0.3s ease;
      border-radius: 12px;
    }
    
    .task-image:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2);
    }

    .date-scroll a {
      transition: all 0.3s ease;
      border-radius: 16px;
    }

    .date-scroll a:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
    }

    .date-scroll a.selected {
      background-color: #fdf2f8;
      color: #ec4899;
      box-shadow: 0 2px 8px rgba(236, 72, 153, 0.2);
    }

    .date-scroll {
      -ms-overflow-style: none;
      scrollbar-width: none;
      padding: 8px 0;
    }

    .date-scroll::-webkit-scrollbar {
      display: none;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.8);
      z-index: 50;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 1;
    }

    .modal-content {
      max-width: 90vw;
      max-height: 90vh;
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      transform: scale(0.9);
      transition: transform 0.3s ease;
    }

    .modal.show .modal-content {
      transform: scale(1);
    }

    .modal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      background: white;
      border-radius: 50%;
      padding: 8px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .modal-close:hover {
      transform: scale(1.1);
      background: #ec4899;
      color: white;
    }

    /* Yeni takvim şeridi stilleri */
    .date-scroll-container {
        position: relative;
        background: white;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .date-scroll {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        padding: 1rem 0;
        scroll-snap-type: x mandatory;
    }

    .date-item {
        scroll-snap-align: center;
        min-width: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .date-circle {
        width: 3.5rem;
        height: 3.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        background: white;
        border: 2px solid transparent;
    }

    .date-item:hover .date-circle {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.1), 0 4px 6px -2px rgba(236, 72, 153, 0.05);
        border-color: #fce7f3;
    }

    .date-item.selected .date-circle {
        background: #ec4899;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.2), 0 4px 6px -2px rgba(236, 72, 153, 0.1);
    }

    .date-item.today .date-circle {
        border-color: #ec4899;
        color: #ec4899;
    }

    .date-item.today:hover .date-circle {
        background: #ec4899;
        color: white;
    }

    .month-divider {
        position: sticky;
        left: 0;
        z-index: 1;
        min-width: auto;
        margin: 0 0.5rem;
        background: linear-gradient(135deg, #fce7f3 0%, #fdf2f8 100%);
        border-radius: 1rem;
        padding: 0.5rem 1rem;
        box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.1), 0 2px 4px -1px rgba(236, 72, 153, 0.06);
    }

    .scroll-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 10;
        opacity: 1;
        transition: opacity 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
    }

    .scroll-button[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .scroll-button:hover {
        background: #fdf2f8;
        color: #ec4899;
        transform: translateY(-50%) scale(1.1);
    }

    .scroll-button.left {
        left: 1rem;
    }

    .scroll-button.right {
        right: 1rem;
    }

    .day-name {
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 0.25rem;
        color: #6b7280;
    }

    .date-number {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .month-name {
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
  </style>
</head>
<body class="bg-gray-100">

<div class="flex flex-col md:flex-row min-h-screen">

  <!-- Sol Menü (Sadece Desktop) -->
  <aside class="desktop-nav w-20 bg-white h-screen py-6 px-2 flex-col items-center shadow hidden md:flex">
    <div class="mb-10">
      <img src="{{ asset('images/pinterest-5-512.png') }}" alt="Logo" class="w-8 h-8">
    </div>
    <nav class="flex flex-col items-center space-y-10">
      <a href="{{ url('/dashboard') }}" title="Anasayfa" class="hover:text-pink-500 text-gray-700">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-5v-6H10v6H5a2 2 0 0 1-2-2v-9z"/>
        </svg>
      </a>
      <a href="{{ url('/add') }}" title="Pin Oluştur" class="hover:text-pink-500 text-gray-700">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 13H13v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
        </svg>
      </a>
      <a href="{{ url('/saved') }}" title="Kaydet" class="hover:text-pink-500 text-gray-700">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/>
        </svg>
      </a>
      <a href="{{ url('/todo') }}" title="Görevler" class="text-pink-500">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
          <path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/>
        </svg>
      </a>
    </nav>
  </aside>

  <!-- İçerik -->
  <div class="flex-1 flex flex-col">
    <header class="bg-white px-4 py-3 shadow z-50 flex items-center sticky top-0">
      <h1 class="text-xl font-bold text-gray-800">Görevler</h1>
    </header>

    <!-- Tarih Seçici -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-2xl mx-auto px-4">
        <div class="flex items-center justify-between py-4">
          <h1 class="text-xl font-bold text-gray-800">Görevler</h1>
        </div>

        <!-- Takvim Şeridi -->
        <div class="date-scroll-container">
            <button onclick="scrollDates('left')" class="scroll-button left">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="overflow-x-auto date-scroll" id="dateScroller">
                <div class="flex space-x-2 px-12">
                    @php
                        $today = now()->setYear($fixedYear);
                        $startDate = $today->copy()->startOfYear();
                        $endDate = $today->copy()->endOfYear();
                        $dates = collect();
                        
                        for ($date = $startDate; $date->lte($endDate); $date = $date->copy()->addDay()) {
                            $dates->push($date->copy());
                        }
                        
                        $selectedDate = request()->query('date', $today->toDateString());
                    @endphp

                    @foreach($dates as $date)
                        @php
                            $isToday = $date->toDateString() === $today->toDateString();
                            $isSelected = $date->toDateString() === $selectedDate;
                            $isFirstOfMonth = $date->day === 1;
                        @endphp
                        
                        @if($isFirstOfMonth)
                            <div class="month-divider flex flex-col items-center justify-center">
                                <span class="text-pink-600 font-semibold">{{ $date->format('F') }}</span>
                            </div>
                        @endif
                        
                        <a href="{{ url('/todo?date=' . $date->toDateString()) }}"
                           class="date-item {{ $isSelected ? 'selected' : '' }} {{ $isToday ? 'today' : '' }}">
                            <span class="month-name">{{ $date->format('M') }}</span>
                            <div class="date-circle">
                                <span class="date-number">{{ $date->format('d') }}</span>
                            </div>
                            <span class="day-name">{{ $date->format('D') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <button onclick="scrollDates('right')" class="scroll-button right">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
      </div>
    </div>

    <!-- Görevler -->
    <main class="flex-1 p-4 pb-20">
      <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-800">
            {{ Carbon\Carbon::parse($selectedDate ?? now())->format('d F Y') }} Görevleri
          </h3>
        </div>

        <!-- Yeni Görev Ekleme -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
          <form onsubmit="addTask(event)" class="flex items-center gap-3">
            <input type="text" id="taskInput" placeholder="Yeni görev ekle..." required
                   class="flex-1 bg-transparent focus:outline-none text-gray-800">
            <button type="submit" class="text-pink-500 hover:text-pink-600">
              Ekle
            </button>
          </form>
        </div>

        <!-- Görev Listesi -->
        <div class="space-y-2">
          @forelse($todos ?? [] as $todo)
            <div class="task-item group bg-white rounded-lg shadow-sm hover:shadow-md transition-all">
              <div class="p-4 flex items-center gap-4">
                <label class="flex items-center cursor-pointer">
                  <input type="checkbox" 
                         class="custom-checkbox"
                         onchange="toggleTaskDone(this)" 
                         {{ $todo->completed ? 'checked' : '' }}
                         data-id="{{ $todo->id }}">
                </label>
                <span class="task-text flex-1 text-gray-800 {{ $todo->completed ? 'task-done' : '' }}">{{ $todo->title }}</span>
                
                @if($todo->image_data)
                  <div class="task-image rounded-lg overflow-hidden flex-shrink-0 shadow-sm hover:shadow-md transition-all"
                       onclick="openModal(this)">
                    <img src="{{ is_string($todo->image_data) ? $todo->image_data : (is_array($todo->image_data) ? $todo->image_data['url'] : '') }}" 
                         alt="Görev fotoğrafı" 
                         class="w-full h-full object-cover">
                  </div>
                @endif

                <div class="task-actions flex items-center gap-2">
                  <button onclick="addPhoto(this)" 
                          class="task-action-btn photo" 
                          title="Fotoğraf Ekle">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">Fotoğraf</span>
                  </button>
                  <button onclick="editTask(this)" 
                          class="task-action-btn edit" 
                          title="Düzenle">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="text-sm">Düzenle</span>
                  </button>
                  <button onclick="deleteTask(this)" 
                          class="task-action-btn delete" 
                          title="Sil">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="text-sm">Sil</span>
                  </button>
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-12">
              <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-800 mb-2">Bu tarih için görev yok</h3>
              <p class="text-gray-500">Yeni bir görev eklemek için yukarıdaki formu kullanın</p>
            </div>
          @endforelse
        </div>
      </div>
    </main>

    <!-- Mobil Alt Menü -->
    <nav class="md:hidden bg-white border-t border-gray-200 fixed bottom-0 left-0 right-0 z-50">
      <div class="flex justify-around">
        <a href="{{ url('/dashboard') }}" class="flex flex-col items-center py-2 text-gray-700">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3 12l9-9 9 9v9a2 2 0 0 1-2 2h-5v-6H10v6H5a2 2 0 0 1-2-2v-9z"/>
          </svg>
          <span class="text-xs mt-1">Ana Sayfa</span>
        </a>
        <a href="{{ url('/add') }}" class="flex flex-col items-center py-2 text-gray-700">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 13H13v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          <span class="text-xs mt-1">Ekle</span>
        </a>
        <a href="{{ url('/saved') }}" class="flex flex-col items-center py-2 text-gray-700">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/>
          </svg>
          <span class="text-xs mt-1">Kaydedilenler</span>
        </a>
        <a href="{{ url('/todo') }}" class="flex flex-col items-center py-2 text-pink-500">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/>
          </svg>
          <span class="text-xs mt-1">Görevler</span>
        </a>
      </div>
    </nav>
  </div>
</div>

<!-- Modal -->
<div id="imageModal" class="modal" onclick="closeModal(event)">
    <div class="modal-content">
        <img id="modalImage" src="" alt="Tam boyut görsel" class="rounded-lg">
        <button class="modal-close" onclick="closeModal(event)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- Görsel ile Görev Ekleme Modal -->
<div id="addTaskWithImageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 relative">
        <button onclick="closeAddTaskModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <h2 class="text-xl font-semibold mb-4">Görsel ile Görev Ekle</h2>
        
        <div class="mb-4">
            <img id="taskImage" src="" alt="Görev görseli" class="w-full h-48 object-cover rounded-lg">
        </div>
        
        <form onsubmit="saveTaskWithImage(event)" class="space-y-4">
            <div>
                <label for="taskTitle" class="block text-sm font-medium text-gray-700 mb-1">Görev Başlığı</label>
                <input type="text" id="taskTitle" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                       placeholder="Görev için başlık girin...">
            </div>
            
            <input type="hidden" id="imageUrl">
            <input type="hidden" id="photographer">
            <input type="hidden" id="imageAlt">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAddTaskModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    İptal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-pink-500 text-white rounded-md hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                    Görevi Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bugüne Git Butonu -->
<button id="todayButton" onclick="scrollToToday()" 
        class="fixed bottom-6 right-6 bg-pink-500 text-white rounded-full p-3 shadow-lg hover:bg-pink-600 transition-all z-50 flex items-center space-x-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <span class="font-medium">Bugün</span>
</button>

<script>
  function addTask(e) {
    e.preventDefault();
    const input = document.getElementById("taskInput");
    const list = document.querySelector('.space-y-2');

    if (input.value.trim() === "") return;

    // Backend'e kaydet
    fetch('/todos', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        title: input.value.trim(),
        due_date: '{{ $selectedDate ?? now()->toDateString() }}'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = data.redirect;
      }
    })
    .catch(error => console.error('Error:', error));
  }

  function toggleTaskDone(checkbox) {
    const taskText = checkbox.closest('label').nextElementSibling;
    
    // Animasyonlu geçiş efekti
    if (checkbox.checked) {
        taskText.classList.add('task-done');
    } else {
        taskText.classList.remove('task-done');
    }

    // Backend'e güncelleme gönder
    const todoId = checkbox.dataset.id;
    fetch(`/todos/${todoId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            completed: checkbox.checked
        })
    })
    .catch(error => console.error('Error:', error));
  }

  function editTask(button) {
    const taskItem = button.closest('.task-item');
    const taskText = taskItem.querySelector('.text-gray-800');
    const currentText = taskText.textContent;
    
    const input = document.createElement('input');
    input.type = 'text';
    input.value = currentText;
    input.className = 'w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-400';
    
    taskText.replaceWith(input);
    input.focus();

    input.addEventListener('blur', function() {
      const newText = input.value.trim();
      if (newText && newText !== currentText) {
        // Backend'e güncelleme gönder
        const todoId = taskItem.dataset.id;
        fetch(`/todos/${todoId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            title: newText
          })
        })
        .then(() => {
          const span = document.createElement('span');
          span.className = taskText.className;
          span.textContent = newText;
          input.replaceWith(span);
        })
        .catch(error => console.error('Error:', error));
      } else {
        input.replaceWith(taskText);
      }
    });

    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        input.blur();
      }
    });
  }

  function deleteTask(button) {
    if (!confirm('Bu görevi silmek istediğinizden emin misiniz?')) return;

    const taskItem = button.closest('.task-item');
    const todoId = taskItem.querySelector('.custom-checkbox').dataset.id;

    fetch(`/todos/${todoId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Silme işlemi başarısız oldu');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        taskItem.style.opacity = '0';
        taskItem.style.transform = 'translateX(100px)';
        setTimeout(() => {
          taskItem.remove();
          // Eğer son görev silindiyse boş durum mesajını göster
          const remainingTasks = document.querySelectorAll('.task-item');
          if (remainingTasks.length === 0) {
            const emptyState = `
              <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                  <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                  </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Bu tarih için görev yok</h3>
                <p class="text-gray-500">Yeni bir görev eklemek için yukarıdaki formu kullanın</p>
              </div>
            `;
            document.querySelector('.space-y-2').innerHTML = emptyState;
          }
        }, 300);
      } else {
        throw new Error(data.message || 'Bir hata oluştu');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Görev silinirken bir hata oluştu: ' + error.message);
    });
  }

  function scrollDates(direction) {
    const scroller = document.getElementById('dateScroller');
    const dateWidth = 100; // Tek bir tarih öğesinin genişliği (padding dahil)
    const visibleDates = 7; // Görünür tarih sayısı
    const scrollAmount = dateWidth * visibleDates; // Bir seferde 7 tarih kaydır
    
    if (direction === 'left') {
        scroller.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    } else {
        scroller.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
  }

  function scrollToDate(dateElement) {
    if (!dateElement) return;
    
    const scroller = document.getElementById('dateScroller');
    const scrollerRect = scroller.getBoundingClientRect();
    const dateRect = dateElement.getBoundingClientRect();
    
    // Tarihin merkeze gelmesi için gerekli scroll pozisyonu
    const centerPosition = dateRect.left - scrollerRect.left - (scrollerRect.width / 2) + (dateRect.width / 2);
    
    scroller.scrollBy({
        left: centerPosition,
        behavior: 'smooth'
    });
  }

  function scrollToToday() {
    const today = document.querySelector('.date-item.today');
    if (today) {
        scrollToDate(today);
    }
  }

  function addPhoto(button) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.style.display = 'none';
    document.body.appendChild(input);

    input.addEventListener('change', async function() {
        const file = input.files[0];
        if (file) {
            const taskItem = button.closest('.task-item');
            const todoId = taskItem.querySelector('.custom-checkbox').dataset.id;
            const taskContent = taskItem.querySelector('.p-4');

            // Dosya boyutu kontrolü (maksimum 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Dosya boyutu çok büyük. Lütfen 5MB\'dan küçük bir dosya seçin.');
                document.body.removeChild(input);
                return;
            }

            // Yükleme göstergesi
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'text-pink-500 text-sm ml-2 flex items-center';
            loadingDiv.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Fotoğraf yükleniyor...
            `;
            taskItem.querySelector('.task-actions').appendChild(loadingDiv);

            try {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const response = await fetch(`/todos/${todoId}/image`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Sunucu yanıt vermedi');
                }

                const data = await response.json();

                if (data.success) {
                    // Görsel önizleme oluştur veya güncelle
                    let imagePreview = taskContent.querySelector('.task-image');
                    if (!imagePreview) {
                        imagePreview = document.createElement('div');
                        imagePreview.className = 'task-image rounded-lg overflow-hidden flex-shrink-0 shadow-sm hover:shadow-md transition-all';
                        imagePreview.onclick = () => openModal(imagePreview);
                        
                        // Görseli task actions'dan önce ekle
                        const taskActions = taskContent.querySelector('.task-actions');
                        taskContent.insertBefore(imagePreview, taskActions);
                    }
                    
                    imagePreview.innerHTML = `
                        <img src="${URL.createObjectURL(file)}" 
                             alt="Görev fotoğrafı" 
                             class="w-full h-full object-cover">
                    `;
                } else {
                    throw new Error(data.message || 'Fotoğraf yüklenemedi');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Fotoğraf yüklenirken bir hata oluştu: ' + error.message);
            } finally {
                loadingDiv.remove();
            }
        }
        document.body.removeChild(input);
    });

    input.click();
  }

  function openModal(imageContainer) {
    const modalImage = document.getElementById('modalImage');
    const modal = document.getElementById('imageModal');
    const img = imageContainer.querySelector('img');
    
    modalImage.src = img.src;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Arka planı kaydırmayı engelle
  }

  function closeModal(event) {
    const modal = document.getElementById('imageModal');
    // Sadece modal arka planına veya kapatma butonuna tıklandığında kapat
    if (event.target === modal || event.target.closest('.modal-close')) {
        modal.classList.remove('show');
        document.body.style.overflow = ''; // Kaydırmayı tekrar etkinleştir
    }
  }

  // ESC tuşu ile modalı kapatma
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
  });

  function showAddTaskModal() {
    const params = new URLSearchParams(window.location.search);
    const imageUrl = params.get('image_url');
    const photographer = params.get('photographer');
    const alt = params.get('alt');
    
    if (imageUrl) {
        document.getElementById('taskImage').src = imageUrl;
        document.getElementById('imageUrl').value = imageUrl;
        document.getElementById('photographer').value = photographer || '';
        document.getElementById('imageAlt').value = alt || '';
        
        const modal = document.getElementById('addTaskWithImageModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
  }

  function closeAddTaskModal() {
    const modal = document.getElementById('addTaskWithImageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
  }

  async function saveTaskWithImage(event) {
    event.preventDefault();
    
    const title = document.getElementById('taskTitle').value;
    const imageUrl = document.getElementById('imageUrl').value;
    const photographer = document.getElementById('photographer').value;
    const imageAlt = document.getElementById('imageAlt').value;
    
    const imageData = {
        url: imageUrl,
        photographer: photographer,
        alt: imageAlt
    };
    
    try {
        const response = await fetch('/todos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                due_date: '{{ $selectedDate ?? now()->toDateString() }}',
                image_data: imageData
            })
        });
        
        if (!response.ok) {
            throw new Error('Görev eklenirken bir hata oluştu');
        }
        
        const data = await response.json();
        if (data.success) {
            window.location.href = data.redirect;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Görev eklenirken bir hata oluştu: ' + error.message);
    }
  }

  // URL'de görsel parametreleri varsa modalı göster
  document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.has('image_url')) {
        showAddTaskModal();
    }
  });

  // Sayfa yüklendiğinde bugünün tarihine otomatik kaydır
  document.addEventListener('DOMContentLoaded', () => {
    const today = document.querySelector('.date-item.today');
    const selected = document.querySelector('.date-item.selected');
    const targetDate = selected || today;

    if (targetDate) {
        // Sayfa yüklendiğinde biraz gecikme ile kaydır (animasyonların düzgün çalışması için)
        setTimeout(() => {
            scrollToDate(targetDate);
        }, 100);
    }

    // Kaydırma olayını dinle ve scroll butonlarını göster/gizle
    const scroller = document.getElementById('dateScroller');
    const leftButton = document.querySelector('.scroll-button.left');
    const rightButton = document.querySelector('.scroll-button.right');

    scroller.addEventListener('scroll', () => {
        // Sol buton görünürlüğü
        if (scroller.scrollLeft <= 0) {
            leftButton.style.opacity = '0.5';
            leftButton.style.cursor = 'not-allowed';
        } else {
            leftButton.style.opacity = '1';
            leftButton.style.cursor = 'pointer';
        }

        // Sağ buton görünürlüğü
        if (scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth) {
            rightButton.style.opacity = '0.5';
            rightButton.style.cursor = 'not-allowed';
        } else {
            rightButton.style.opacity = '1';
            rightButton.style.cursor = 'pointer';
        }
    });
  });

  // Bugüne git butonu metnini güncelle
  document.addEventListener('DOMContentLoaded', () => {
    const todayButton = document.querySelector('#todayButton');
    if (todayButton) {
        const today = new Date();
        todayButton.querySelector('span').textContent = `${today.getDate()} ${['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'][today.getMonth()]} 2025`;
    }
  });
</script>

</body>
</html>
