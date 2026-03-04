@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-12 pb-24">
        <div class="px-4">
            <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900 italic">Hi, {{ Auth::user()->name }}
                👋</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Welcome to your workspace dashboard. Have a
                productive day!</p>
        </div>

        <!-- Attendance Card -->
        <div
            class="relative overflow-hidden bg-white rounded-[40px] shadow-2xl shadow-indigo-100 p-10 border border-slate-100">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>

            <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="space-y-2">
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500">Today's Status</p>
                    <h2 class="text-5xl font-black text-slate-900 tracking-tighter tabular-nums" id="realtime-clock">
                        00:00:00</h2>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">{{ date('l, d F Y') }}</p>
                </div>

                <div class="flex flex-col gap-6 w-full md:w-auto">
                    <!-- Camera Preview -->
                    <div id="camera-container"
                        class="hidden relative w-full md:w-80 aspect-video bg-slate-100 rounded-3xl overflow-hidden ring-4 ring-indigo-50 border border-slate-200">
                        <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        <div class="absolute inset-x-0 bottom-4 flex justify-center">
                            <button type="button" id="capture-btn"
                                class="bg-indigo-600 hover:bg-slate-900 text-white font-black px-6 py-2 rounded-2xl shadow-lg transition-all active:scale-95 uppercase tracking-widest text-[10px]">
                                Capture & Confirm
                            </button>
                        </div>
                    </div>

                    <div id="action-buttons" class="flex gap-4 w-full">
                        @if(!$todayAttendance || !$todayAttendance->clock_in)
                            <button type="button" onclick="startCamera('in')"
                                class="w-full inline-flex items-center justify-center rounded-3xl bg-indigo-600 px-10 py-5 text-lg font-black text-white shadow-xl shadow-indigo-200 hover:bg-slate-900 transition-all active:scale-95 uppercase tracking-widest">
                                Clock In
                            </button>
                        @elseif(!$todayAttendance->clock_out)
                            <button type="button" onclick="startCamera('out')"
                                class="w-full inline-flex items-center justify-center rounded-3xl bg-rose-600 px-10 py-5 text-lg font-black text-white shadow-xl shadow-rose-200 hover:bg-slate-900 transition-all active:scale-95 uppercase tracking-widest">
                                Clock Out
                            </button>
                        @else
                            <div
                                class="w-full px-8 py-5 rounded-3xl bg-slate-50 border border-slate-100 text-slate-400 font-black uppercase tracking-widest text-sm text-center">
                                Work Completed
                            </div>
                        @endif
                    </div>

                    <form id="attendance-form" action="" method="POST" class="hidden">
                        @csrf
                        <input type="hidden" name="image" id="image-input">
                        <input type="hidden" name="location" id="location-input">
                    </form>
                </div>
            </div>

            @if($todayAttendance)
                <div class="mt-12 grid grid-cols-2 gap-4 border-t border-slate-50 pt-8">
                    <div class="p-4 bg-emerald-50/50 rounded-3xl border border-emerald-100 relative overflow-hidden group">
                        @if($todayAttendance->image_in)
                            <img src="{{ asset('storage/' . $todayAttendance->image_in) }}"
                                class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-100 transition-all duration-500">
                        @endif
                        <div class="relative">
                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-1">Clocked In At</p>
                            <p class="text-2xl font-black text-emerald-900 tabular-nums">
                                {{ $todayAttendance->clock_in ?? '--:--' }}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-rose-50/50 rounded-3xl border border-rose-100 relative overflow-hidden group">
                        @if($todayAttendance->image_out)
                            <img src="{{ asset('storage/' . $todayAttendance->image_out) }}"
                                class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-100 transition-all duration-500">
                        @endif
                        <div class="relative">
                            <p class="text-[10px] font-black uppercase tracking-widest text-rose-600 mb-1">Clocked Out At</p>
                            <p class="text-2xl font-black text-rose-900 tabular-nums">
                                {{ $todayAttendance->clock_out ?? '--:--' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="#"
                class="group p-8 bg-slate-900 rounded-[32px] hover:bg-indigo-600 transition-all shadow-xl hover:-translate-y-2">
                <div
                    class="h-12 w-12 rounded-2xl bg-white/10 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .401.127.773.344 1.077l.216.304c.145.205.145.474 0 .68l-.216.304c-.217.304-.344.676-.344 1.077 0 .231.035.454.1.664m-.1-4.77h4.77c.621 0 1.125.504 1.125 1.125v4.77m-1.125-4.77l-4.77 4.77m0-4.77v4.77m4.77-4.77v4.77" />
                    </svg>
                </div>
                <h3 class="text-white font-black text-sm uppercase tracking-widest">Payslips</h3>
                <p class="mt-2 text-white/50 text-xs font-medium">Coming Soon</p>
            </a>

            <a href="#"
                class="group p-8 bg-white rounded-[32px] hover:bg-slate-50 transition-all border border-slate-100 shadow-sm hover:-translate-y-2">
                <div
                    class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 mb-6 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <h3 class="text-slate-900 font-black text-sm uppercase tracking-widest">Time Off</h3>
                <p class="mt-2 text-slate-500 text-xs font-medium">Coming Soon</p>
            </a>
        </div>
    </div>

    <script>
        let stream;
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const cameraContainer = document.getElementById('camera-container');
        const actionButtons = document.getElementById('action-buttons');
        const attendanceForm = document.getElementById('attendance-form');
        const imageInput = document.getElementById('image-input');
        const locationInput = document.getElementById('location-input');

        async function startCamera(type) {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                video.srcObject = stream;
                cameraContainer.classList.remove('hidden');
                actionButtons.classList.add('hidden');
                
                attendanceForm.action = type === 'in' ? "{{ route('ess.attendance.clock-in') }}" : "{{ route('ess.attendance.clock-out') }}";
                
                // Get location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        locationInput.value = `${position.coords.latitude}, ${position.coords.longitude}`;
                    });
                }
            } catch (err) {
                console.error("Error accessing camera: ", err);
                alert("Please allow camera access to clock in/out.");
            }
        }

        captureBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg');
            imageInput.value = imageData;
            
            // Stop stream
            stream.getTracks().forEach(track => track.stop());
            
            // Submit form
            attendanceForm.submit();
        });

        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('realtime-clock').textContent = time;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endsection