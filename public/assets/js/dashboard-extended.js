let orderStatusChart;

// Initialize extended features
document.addEventListener("DOMContentLoaded", function () {
    loadDashboardData();
    initOrderStatusChart();
    loadRecentOrders();
    loadPerformanceMetrics();
    initSalesChart();
});

// Load main dashboard stats
async function loadDashboardData() {
    try {
        const response = await fetch("/admin/dashboard-stats");
        const data = await response.json();
        
        if (data.total_revenue !== undefined) {
            document.getElementById("totalRevenue").textContent = 
                "$" + formatCurrency(data.total_revenue);
        }
        if (data.revenue_growth !== undefined) {
            const growthElem = document.getElementById("revenueGrowth");
            const iconElem = document.getElementById("growthIcon");
            growthElem.textContent = data.revenue_growth + "%";
            if (data.revenue_growth > 0) {
                growthElem.className = "text-green-500 font-semibold";
                iconElem.className = "fas fa-arrow-up text-green-500";
            } else if (data.revenue_growth < 0) {
                growthElem.className = "text-red-500 font-semibold";
                iconElem.className = "fas fa-arrow-down text-red-500";
            } else {
                growthElem.className = "text-gray-500 font-semibold";
                iconElem.className = "fas fa-minus text-gray-500";
            }
        }
        if (data.active_orders !== undefined) {
            document.getElementById("activeOrders").textContent = data.active_orders;
        }
        if (data.orders_today !== undefined) {
            document.getElementById("ordersToday").textContent = data.orders_today;
        }
        if (data.total_products !== undefined) {
            document.getElementById("totalProducts").textContent = data.total_products;
        }
        if (data.low_stock !== undefined) {
            document.getElementById("lowStock").textContent = data.low_stock;
        }
        if (data.total_customers !== undefined) {
            document.getElementById("totalCustomers").textContent = data.total_customers;
        }
        if (data.new_customers !== undefined) {
            document.getElementById("newCustomers").textContent = data.new_customers;
        }
        if (data.inventory_percent !== undefined) {
            document.getElementById("inventoryPercent").textContent = data.inventory_percent + "%";
            const bar = document.getElementById("inventoryBar");
            if (bar) {
                setTimeout(() => {
                    bar.style.width = data.inventory_percent + "%";
                }, 300);
            }
        }
        if (data.pending_shipments !== undefined) {
            document.getElementById("pendingShipments").textContent = data.pending_shipments;
        }
        
    } catch (error) {
        console.error("Error loading dashboard data:", error);
    }
}

// Initialize Order Status Donut Chart
async function initOrderStatusChart() {
    const canvas = document.getElementById("orderStatusChart");
    if (!canvas) return;

    try {
        const response = await fetch("/admin/order-status-distribution");
        const data = await response.json();

        const ctx = canvas.getContext("2d");

        // Use your actual status labels from the backend
        const statusLabels = data.order_status_labels || [
            "Pending",
            "Confirmed",
            "Processing", 
            "Shipped",
            "Delivered",
            "Cancelled",
            "Refunded"
        ];

        orderStatusChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: statusLabels,
                datasets: [
                    {
                        data: data.order_status_distribution || [0, 0, 0, 0, 0, 0, 0],
                        backgroundColor: [
                            "#F59E0B", // Amber - Pending
                            "#F97316", // Orange - Confirmed
                            "#3B82F6", // Blue - Processing
                            "#8B5CF6", // Purple - Shipped
                            "#10B981", // Green - Delivered
                            "#EF4444", // Red - Cancelled
                            "#6B7280"  // Gray - Refunded
                        ],
                        borderWidth: 0,
                        hoverOffset: 10,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                            },
                            usePointStyle: true,
                            pointStyle: "circle",
                        },
                    },
                    tooltip: {
                        backgroundColor: "rgba(0, 0, 0, 0.8)",
                        padding: 12,
                        callbacks: {
                            label: function (context) {
                                const label = context.label || "";
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce(
                                    (a, b) => a + b,
                                    0
                                );
                                const percentage = total > 0 
                                    ? ((value / total) * 100).toFixed(1)
                                    : "0.0";
                                return `${label}: ${value} (${percentage}%)`;
                            },
                        },
                    },
                },
                cutout: "65%",
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: "easeInOutQuart",
                },
            },
        });
    } catch (error) {
        console.error("Error loading order status chart:", error);
        // Use default data if API fails
        createDefaultOrderStatusChart(canvas);
    }
}

// Create default order status chart (fallback)
function createDefaultOrderStatusChart(canvas) {
    const ctx = canvas.getContext("2d");

    orderStatusChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: [
                "Pending",
                "Confirmed",
                "Processing",
                "Shipped", 
                "Delivered",
                "Cancelled",
                "Refunded"
            ],
            datasets: [
                {
                    data: [10, 5, 8, 12, 15, 3, 2],
                    backgroundColor: [
                        "#F59E0B", // Amber - Pending
                        "#F97316", // Orange - Confirmed
                        "#3B82F6", // Blue - Processing
                        "#8B5CF6", // Purple - Shipped
                        "#10B981", // Green - Delivered
                        "#EF4444", // Red - Cancelled
                        "#6B7280"  // Gray - Refunded
                    ],
                    borderWidth: 0,
                    hoverOffset: 10,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 15,
                        font: { size: 12 },
                        usePointStyle: true,
                        pointStyle: "circle",
                    },
                },
            },
            cutout: "65%",
        },
    });
}

// Initialize Sales Chart
async function initSalesChart() {
    const canvas = document.getElementById("salesChart");
    if (!canvas) return;

    try {
        const response = await fetch("/admin/sales-chart?period=week");
        const data = await response.json();

        const ctx = canvas.getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: data.labels || [],
                datasets: [{
                    label: "Sales",
                    data: data.data || [],
                    borderColor: "#3B82F6",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#3B82F6",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `Sales: $${formatCurrency(context.parsed.y)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.05)"
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + formatCurrency(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Update period buttons
        updatePeriodButtons('week');

    } catch (error) {
        console.error("Error loading sales chart:", error);
    }
}

// Update Sales Chart Period
async function updateSalesChart(period) {
    try {
        const response = await fetch(`/admin/sales-chart?period=${period}`);
        const data = await response.json();
        
        // Update the chart data
        const chart = Chart.getChart("salesChart");
        if (chart) {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.data;
            chart.update();
        }

        // Update the summary
        if (document.getElementById("salesTotal")) {
            document.getElementById("salesTotal").textContent = 
                "$" + formatCurrency(data.total_sales || 0);
        }
        if (document.getElementById("salesAverage")) {
            document.getElementById("salesAverage").textContent = 
                "$" + formatCurrency(data.average_sales || 0);
        }

        // Update period buttons
        updatePeriodButtons(period);

    } catch (error) {
        console.error("Error updating sales chart:", error);
    }
}

// Update active period button
function updatePeriodButtons(activePeriod) {
    const buttons = document.querySelectorAll('.period-btn');
    buttons.forEach(btn => {
        if (btn.dataset.period === activePeriod) {
            btn.classList.add('bg-blue-500', 'text-white');
            btn.classList.remove('bg-gray-100', 'text-gray-600');
        } else {
            btn.classList.add('bg-gray-100', 'text-gray-600');
            btn.classList.remove('bg-blue-500', 'text-white');
        }
    });
}

// Load Recent Orders
async function loadRecentOrders() {
    const tableBody = document.getElementById("recentOrdersList");
    if (!tableBody) return;

    try {
        const response = await fetch("/admin/recent-activity");
        const data = await response.json();

        const orders = data.recent_orders || [];

        if (orders.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>No recent orders</p>
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = orders
            .map(
                (order) => `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="py-3 px-4">
                    <span class="font-medium text-gray-900">#${
                        order.order_number || order.id
                    }</span>
                </td>
                <td class="py-3 px-4">
                    <span class="text-gray-700">${
                        order.user ? order.user.name : "Guest"
                    }</span>
                </td>
                <td class="py-3 px-4">
                    <span class="font-semibold text-gray-900">$${formatCurrency(
                        order.total_amount || 0
                    )}</span>
                </td>
                <td class="py-3 px-4">
                    ${getStatusBadge(order.order_status || 'pending', order.payment_status || 'pending')}
                </td>
                <td class="py-3 px-4">
                    <span class="text-sm text-gray-600">${formatDate(
                        order.created_at
                    )}</span>
                </td>
            </tr>
        `
            )
            .join("");
    } catch (error) {
        console.error("Error loading recent orders:", error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Failed to load orders</p>
                </td>
            </tr>
        `;
    }
}

// Get status badge HTML with combined order and payment status
function getStatusBadge(orderStatus, paymentStatus) {
    const orderBadges = {
        pending: {
            text: 'Pending',
            class: 'bg-yellow-100 text-yellow-700'
        },
        confirmed: {
            text: 'Confirmed',
            class: 'bg-orange-100 text-orange-700'
        },
        processing: {
            text: 'Processing',
            class: 'bg-blue-100 text-blue-700'
        },
        shipped: {
            text: 'Shipped',
            class: 'bg-purple-100 text-purple-700'
        },
        delivered: {
            text: 'Delivered',
            class: 'bg-green-100 text-green-700'
        },
        cancelled: {
            text: 'Cancelled',
            class: 'bg-red-100 text-red-700'
        },
        refunded: {
            text: 'Refunded',
            class: 'bg-gray-100 text-gray-700'
        }
    };

    const paymentBadges = {
        pending: {
            text: 'Payment Pending',
            class: 'bg-yellow-100 text-yellow-700'
        },
        paid: {
            text: 'Paid',
            class: 'bg-green-100 text-green-700'
        },
        failed: {
            text: 'Payment Failed',
            class: 'bg-red-100 text-red-700'
        },
        refunded: {
            text: 'Refunded',
            class: 'bg-gray-100 text-gray-700'
        }
    };

    const orderBadge = orderBadges[orderStatus] || orderBadges.pending;
    const paymentBadge = paymentBadges[paymentStatus] || paymentBadges.pending;

    // Show payment status if it's not paid, otherwise show order status
    if (paymentStatus !== 'paid') {
        return `<span class="px-2 py-1 text-xs font-medium rounded-full ${paymentBadge.class}">${paymentBadge.text}</span>`;
    }
    
    return `<span class="px-2 py-1 text-xs font-medium rounded-full ${orderBadge.class}">${orderBadge.text}</span>`;
}

// Load Recent Customers
async function loadRecentCustomers() {
    const container = document.getElementById("recentCustomersList");
    if (!container) return;

    try {
        const response = await fetch("/admin/recent-activity");
        const data = await response.json();

        const customers = data.recent_customers || [];

        if (customers.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-users text-4xl mb-2"></i>
                    <p>No recent customers</p>
                </div>
            `;
            return;
        }

        container.innerHTML = customers
            .map(
                (customer) => `
            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">${customer.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">${customer.name}</h4>
                        <p class="text-sm text-gray-500">${customer.email}</p>
                    </div>
                </div>
                <span class="text-sm text-gray-500">${formatDate(customer.created_at)}</span>
            </div>
        `
            )
            .join("");
    } catch (error) {
        console.error("Error loading recent customers:", error);
        container.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Failed to load customers</p>
            </div>
        `;
    }
}

// Load Performance Metrics
async function loadPerformanceMetrics() {
    try {
        const response = await fetch("/admin/performance-metrics");
        const data = await response.json();

        // Average Order Value
        if (document.getElementById("avgOrderValue")) {
            document.getElementById("avgOrderValue").textContent =
                "$" + formatCurrency(data.avg_order_value || 0);
            const percentage = data.avg_order_value > 0 
                ? Math.min((data.avg_order_value / (data.max_order_value || 10000)) * 100, 100)
                : 0;
            setTimeout(() => {
                const bar = document.getElementById("avgOrderBar");
                if (bar) bar.style.width = percentage + "%";
            }, 500);
        }

        // Conversion Rate
        if (document.getElementById("conversionRate")) {
            document.getElementById("conversionRate").textContent =
                (data.conversion_rate || 0) + "%";
            setTimeout(() => {
                const bar = document.getElementById("conversionBar");
                if (bar) bar.style.width = Math.min((data.conversion_rate || 0) * 3, 100) + "%";
            }, 700);
        }

        // Order Fulfillment
        if (document.getElementById("fulfillment")) {
            document.getElementById("fulfillment").textContent =
                (data.fulfillment_rate || 0) + "%";
            setTimeout(() => {
                const bar = document.getElementById("fulfillmentBar");
                if (bar) bar.style.width = (data.fulfillment_rate || 0) + "%";
            }, 900);
        }

        // Satisfaction Rate
        if (document.getElementById("satisfaction")) {
            document.getElementById("satisfaction").textContent =
                (data.satisfaction_rate || 85) + "%";
            setTimeout(() => {
                const bar = document.getElementById("satisfactionBar");
                if (bar) bar.style.width = (data.satisfaction_rate || 85) + "%";
            }, 1100);
        }

    } catch (error) {
        console.error("Error loading performance metrics:", error);
        // Set default values
        document.querySelectorAll('[id$="Value"], [id$="Rate"], [id$="ment"]').forEach(elem => {
            if (elem.id !== "revenueGrowth") {
                elem.textContent = "0";
            }
        });
    }
}

// Load Top Products
async function loadTopProducts() {
    const container = document.getElementById("topProductsList");
    if (!container) return;

    try {
        const response = await fetch("/admin/top-products");
        const data = await response.json();

        const products = data.top_products || [];

        if (products.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>No products found</p>
                </div>
            `;
            return;
        }

        container.innerHTML = products
            .map(
                (product) => `
            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded overflow-hidden bg-gray-100">
                        ${product.image ? 
                            `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-image"></i>
                            </div>`
                        }
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 truncate max-w-[150px]">${product.name}</h4>
                        <p class="text-sm text-gray-500">${product.total_sold || 0} sold</p>
                    </div>
                </div>
                <span class="text-sm font-medium text-gray-900">$${Math.floor(Math.random() * 100) + 20}</span>
            </div>
        `
            )
            .join("");
    } catch (error) {
        console.error("Error loading top products:", error);
        container.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Failed to load products</p>
            </div>
        `;
    }
}

// Format currency
function formatCurrency(amount) {
    return parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Format date
function formatDate(dateString) {
    if (!dateString) return "N/A";
    
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return "Today";
    if (diffDays === 1) return "Yesterday";
    if (diffDays < 7) return `${diffDays} days ago`;

    return date.toLocaleDateString("en-US", { 
        month: "short", 
        day: "numeric",
        year: date.getFullYear() !== now.getFullYear() ? "numeric" : undefined
    });
}

// Refresh all dashboard data
async function refreshDashboard() {
    console.log("Refreshing dashboard data...");
    
    // Show loading state
    document.querySelectorAll('.refresh-icon').forEach(icon => {
        icon.classList.add('fa-spin');
    });

    try {
        await Promise.all([
            loadDashboardData(),
            loadRecentOrders(),
            loadRecentCustomers(),
            loadTopProducts(),
            loadPerformanceMetrics()
        ]);
        
        // Update charts if they exist
        if (orderStatusChart) {
            const response = await fetch("/admin/order-status-distribution");
            const data = await response.json();
            if (orderStatusChart && data.order_status_distribution) {
                orderStatusChart.data.datasets[0].data = data.order_status_distribution;
                orderStatusChart.update();
            }
        }
    } catch (error) {
        console.error("Error refreshing dashboard:", error);
    } finally {
        // Remove loading state
        setTimeout(() => {
            document.querySelectorAll('.refresh-icon').forEach(icon => {
                icon.classList.remove('fa-spin');
            });
        }, 500);
    }
}

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
    if (!document.hidden) {
        refreshDashboard();
    }
}, 300000); // 5 minutes

// Handle period switching for sales chart
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('period-btn')) {
        const period = e.target.dataset.period;
        updateSalesChart(period);
    }
    
    // Handle refresh button click
    if (e.target.closest('.refresh-btn')) {
        e.preventDefault();
        refreshDashboard();
    }
});

// Load additional data when needed
if (document.getElementById("recentCustomersList")) {
    loadRecentCustomers();
}

if (document.getElementById("topProductsList")) {
    loadTopProducts();
}