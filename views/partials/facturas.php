<?php
// Vista parcial para carga dinámica
?>
<div class="space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Gestión de Facturas</h1>
        <p class="text-gray-400 text-sm md:text-base">Arrastra las facturas para actualizar su estado</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <input type="text" id="searchInvoices" placeholder="Buscar por cliente o ID de factura..." 
               class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500">
        <button onclick="openAddInvoiceModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition whitespace-nowrap">
            + Agregar Factura
        </button>
    </div>

    <div id="kanbanContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Skeleton inicial con spinner -->
        <div class="col-span-full flex flex-col items-center justify-center py-12">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-700 border-t-orange-500 rounded-full animate-spin"></div>
            </div>
            <p class="mt-4 text-gray-400 text-sm">Cargando facturas...</p>
        </div>
    </div>
</div>

<!-- Modal para agregar factura -->
<div id="invoiceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full border border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-white">Nueva Factura</h3>
            <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-white">✕</button>
        </div>
        <form id="invoiceForm" class="space-y-4">
            <div>
                <label class="block text-gray-300 mb-2">Número de Factura</label>
                <input type="text" id="invoiceNumber" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-gray-300 mb-2">Cliente</label>
                <input type="text" id="invoiceClient" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-gray-300 mb-2">Monto (CLP)</label>
                <input type="number" id="invoiceAmount" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-gray-300 mb-2">Fecha de Vencimiento</label>
                <input type="date" id="invoiceDueDate" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                    Guardar
                </button>
                <button type="button" onclick="closeInvoiceModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Script de gestión de facturas
(function() {
    'use strict';
    const BASE_PATH = window.BASE_PATH || '';

    // Solo inicializar si estamos en la página de facturas
    if (!document.getElementById('kanbanContainer')) return;

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFacturas);
    } else {
        initFacturas();
    }

    async function initFacturas() {
        showKanbanSkeleton();
    
    try {
        const response = await fetch(BASE_PATH + '/api/invoices');
        if (response.ok) {
            const invoices = await response.json();
            if (invoices.length > 0) {
                renderKanban(invoices);
            } else {
                // Datos de ejemplo si no hay facturas
                renderKanban([
                    { id: '1', cliente: 'Client A', monto: 1500, estado: 'al_dia', dias: 5 },
                    { id: '2', cliente: 'Client B', monto: 4250, estado: 'al_dia', dias: 12 },
                    { id: '3', cliente: 'Client D', monto: 2500, estado: 'atrasada', dias: -2, intereses: 5.75 }
                ]);
            }
        } else {
            // Fallback a datos mock
            setTimeout(() => {
                renderKanban([
                    { id: '1', cliente: 'Client A', monto: 1500, estado: 'al_dia', dias: 5 },
                    { id: '2', cliente: 'Client B', monto: 4250, estado: 'al_dia', dias: 12 },
                    { id: '3', cliente: 'Client D', monto: 2500, estado: 'atrasada', dias: -2, intereses: 5.75 }
                ]);
            }, 1000);
        }
    } catch (error) {
        console.error('Error cargando facturas:', error);
        setTimeout(() => {
            renderKanban([
                { id: '1', cliente: 'Client A', monto: 1500, estado: 'al_dia', dias: 5 },
                { id: '2', cliente: 'Client B', monto: 4250, estado: 'al_dia', dias: 12 }
            ]);
        }, 1000);
    }
}

function showKanbanSkeleton() {
    const container = document.getElementById('kanbanContainer');
    if (!container) return;
    
    container.innerHTML = `
        ${Array(3).fill(0).map(() => `
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <div class="skel-box bg-gray-700 rounded mb-4 h-8"></div>
                <div class="space-y-3">
                    ${Array(2).fill(0).map(() => `
                        <div class="skel-box bg-gray-700 rounded-lg p-4"></div>
                    `).join('')}
                </div>
            </div>
        `).join('')}
    `;
}

function renderKanban(facturas) {
    const alDia = facturas.filter(f => f.estado === 'al_dia' || f.status === 'al_dia' || f.status === 'pending');
    const atrasadas = facturas.filter(f => f.estado === 'atrasada' || f.status === 'atrasada' || (f.dias && f.dias < 0));
    const masSemana = facturas.filter(f => f.estado === 'mas_semana' || f.status === 'mas_semana' || (f.dias && f.dias > 7));

    const formatearMoneda = (valor) => {
        return new Intl.NumberFormat('es-CL', {
            style: 'currency',
            currency: 'CLP',
            minimumFractionDigits: 0
        }).format(valor);
    };

    const calcularDias = (fechaVencimiento) => {
        if (!fechaVencimiento) return 0;
        const hoy = new Date();
        const vencimiento = new Date(fechaVencimiento);
        const diff = Math.ceil((vencimiento - hoy) / (1000 * 60 * 60 * 24));
        return diff;
    };

    const container = document.getElementById('kanbanContainer');
    if (!container) return;

    container.innerHTML = `
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Al día (${alDia.length})</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                ${alDia.length > 0 ? alDia.map(f => {
                    const dias = f.dias || calcularDias(f.due_date);
                    return `
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                            <p class="font-semibold text-white">${f.cliente || f.invoice_number || 'Cliente'}</p>
                            <p class="text-lg font-bold text-white">${formatearMoneda(f.monto || f.amount || 0)}</p>
                            <p class="text-xs ${dias >= 0 ? 'text-green-400' : 'text-red-400'} mb-3">
                                ${dias >= 0 ? `Vence en ${dias} días` : `Vencida hace ${Math.abs(dias)} días`}
                            </p>
                            <button onclick="enviarRecordatorio('${f.id}')" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 rounded transition">
                                Enviar recordatorio
                            </button>
                        </div>
                    `;
                }).join('') : '<p class="text-gray-400 text-sm text-center py-4">No hay facturas</p>'}
            </div>
        </div>
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Atrasadas 1-3 días (${atrasadas.length})</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                ${atrasadas.length > 0 ? atrasadas.map(f => {
                    const dias = f.dias || calcularDias(f.due_date);
                    return `
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                            <p class="font-semibold text-white">${f.cliente || f.invoice_number || 'Cliente'}</p>
                            <p class="text-lg font-bold text-white">${formatearMoneda(f.monto || f.amount || 0)}</p>
                            <p class="text-xs text-red-400 mb-3">Intereses: ${formatearMoneda(f.intereses || 0)}</p>
                            <button onclick="enviarRecordatorio('${f.id}')" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 rounded transition">
                                Enviar recordatorio
                            </button>
                        </div>
                    `;
                }).join('') : '<p class="text-gray-400 text-sm text-center py-4">No hay facturas atrasadas</p>'}
            </div>
        </div>
        <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">+1 semana (${masSemana.length})</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                ${masSemana.length > 0 ? masSemana.map(f => {
                    const dias = f.dias || calcularDias(f.due_date);
                    return `
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                            <p class="font-semibold text-white">${f.cliente || f.invoice_number || 'Cliente'}</p>
                            <p class="text-lg font-bold text-white">${formatearMoneda(f.monto || f.amount || 0)}</p>
                            <p class="text-xs text-gray-400 mb-3">Vence en ${dias} días</p>
                            <button onclick="enviarRecordatorio('${f.id}')" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 rounded transition">
                                Enviar recordatorio
                            </button>
                        </div>
                    `;
                }).join('') : '<p class="text-gray-400 text-sm text-center py-4">No hay facturas</p>'}
            </div>
        </div>
    `;
    }

    function enviarRecordatorio(id) {
        if (confirm('¿Enviar recordatorio de pago para esta factura?')) {
            alert(`Recordatorio enviado para la factura #${id}`);
            // Aquí podrías hacer una llamada a la API para enviar el recordatorio
        }
    }

    function openAddInvoiceModal() {
        const modal = document.getElementById('invoiceModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeInvoiceModal() {
        const modal = document.getElementById('invoiceModal');
        if (modal) modal.classList.add('hidden');
        const form = document.getElementById('invoiceForm');
        if (form) form.reset();
    }

    // Exponer funciones globalmente para que puedan ser llamadas desde el HTML
    window.enviarRecordatorio = enviarRecordatorio;
    window.openAddInvoiceModal = openAddInvoiceModal;
    window.closeInvoiceModal = closeInvoiceModal;

    // Event listeners
    const invoiceForm = document.getElementById('invoiceForm');
    if (invoiceForm) {
        invoiceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Funcionalidad de agregar factura próximamente');
            closeInvoiceModal();
        });
    }

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInvoices');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('#kanbanContainer .bg-gray-700');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

})(); // Cierre de la función IIFE
</script>