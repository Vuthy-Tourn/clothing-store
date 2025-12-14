let orderStatusChart;

// Initialize extended features
document.addEventListener("DOMContentLoaded", function () {
    initOrderStatusChart();
    loadRecentOrders();
    loadPerformanceMetrics();
});

// Initialize Order Status Donut Chart
async function initOrderStatusChart() {
    const canvas = document.getElementById("orderStatusChart");
    if (!canvas) return;

    try {
        // You can add this endpoint to your controller
        const response = await fetch("/admin/order-status-distribution");
        const data = await response.json();

        const ctx = canvas.getContext("2d");

        orderStatusChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: [
                    "Pending",
                    "Processing",
                    "Shipped",
                    "Completed",
                    "Cancelled",
                ],
                datasets: [
                    {
                        data: data.distribution || [25, 30, 20, 20, 5],
                        backgroundColor: [
                            "#F59E0B", // Amber - Pending
                            "#3B82F6", // Blue - Processing
                            "#8B5CF6", // Purple - Shipped
                            "#10B981", // Green - Completed
                            "#EF4444", // Red - Cancelled
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
                                const percentage = (
                                    (value / total) *
                                    100
                                ).toFixed(1);
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
                "Processing",
                "Shipped",
                "Completed",
                "Cancelled",
            ],
            datasets: [
                {
                    data: [25, 30, 20, 20, 5],
                    backgroundColor: [
                        "#F59E0B",
                        "#3B82F6",
                        "#8B5CF6",
                        "#10B981",
                        "#EF4444",
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
                        order.order_number
                    }</span>
                </td>
                <td class="py-3 px-4">
                    <span class="text-gray-700">${
                        order.user ? order.user.name : "N/A"
                    }</span>
                </td>
                <td class="py-3 px-4">
                    <span class="font-semibold text-gray-900">$${parseFloat(
                        order.total_amount
                    ).toLocaleString()}</span>
                </td>
                <td class="py-3 px-4">
                    ${getStatusBadge(order.status)}
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

// Get status badge HTML
function getStatusBadge(status) {
    const badges = {
        pending:
            '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Pending</span>',
        processing:
            '<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Processing</span>',
        shipped:
            '<span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">Shipped</span>',
        completed:
            '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Completed</span>',
        cancelled:
            '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Cancelled</span>',
    };

    return badges[status] || badges["pending"];
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return "Today";
    if (diffDays === 1) return "Yesterday";
    if (diffDays < 7) return `${diffDays} days ago`;

    return date.toLocaleDateString("en-US", { month: "short", day: "numeric" });
}

// Load Performance Metrics
async function loadPerformanceMetrics() {
    try {
        // These would come from your backend
        // For now, using calculated/sample data
        const metrics = {
            avgOrderValue: 2500,
            maxOrderValue: 5000,
            conversionRate: 3.5,
            fulfillmentRate: 92,
        };

        // Average Order Value
        if (document.getElementById("avgOrderValue")) {
            document.getElementById("avgOrderValue").textContent =
                "$" + metrics.avgOrderValue.toLocaleString();
            const percentage =
                (metrics.avgOrderValue / metrics.maxOrderValue) * 100;
            setTimeout(() => {
                document.getElementById("avgOrderBar").style.width =
                    percentage + "%";
            }, 500);
        }

        // Conversion Rate
        if (document.getElementById("conversionRate")) {
            document.getElementById("conversionRate").textContent =
                metrics.conversionRate + "%";
            setTimeout(() => {
                document.getElementById("conversionBar").style.width =
                    metrics.conversionRate * 10 + "%";
            }, 700);
        }

        // Order Fulfillment
        if (document.getElementById("fulfillment")) {
            document.getElementById("fulfillment").textContent =
                metrics.fulfillmentRate + "%";
            setTimeout(() => {
                document.getElementById("fulfillmentBar").style.width =
                    metrics.fulfillmentRate + "%";
            }, 900);
        }
    } catch (error) {
        console.error("Error loading performance metrics:", error);
    }
}

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
    console.log("Auto-refreshing dashboard data...");
    loadDashboardData();
    loadRecentOrders();
}, 300000); // 5 minutes
