// Modern Creative Dashboard Charts - Redesigned Version
// public/assets/js/dashboard.js

let salesChart = null;
let topProductsChart;
let orderStatusChart;
let revenueComparisonChart;
let currentPeriod = "week";

// Modern creative color palette (no gradients)
const creativePalette = {
    primary: "#4F46E5", // Indigo
    secondary: "#7C3AED", // Purple
    success: "#059669", // Emerald
    warning: "#D97706", // Amber
    danger: "#DC2626", // Red
    info: "#0891B2", // Cyan
    dark: "#1F2937", // Gray 800
    light: "#F9FAFB", // Gray 50

    // Chart color sets
    barColors: [
        "#4F46E5",
        "#7C3AED",
        "#EC4899",
        "#F59E0B",
        "#10B981",
        "#3B82F6",
        "#8B5CF6",
        "#EF4444",
        "#06B6D4",
        "#84CC16",
    ],
    lineColors: ["#4F46E5", "#7C3AED", "#EC4899", "#F59E0B", "#10B981"],
    donutColors: ["#F59E0B", "#4F46E5", "#7C3AED", "#10B981", "#EF4444"],
};

const creativePaletteOfOrderStatus = {
    dark: "#2E2E2E",
    donutColors: [
        "#A3AED0", // Pending - gray/blue
        "#FACC15", // Processing - yellow
        "#6366F1", // Shipped - indigo
        "#22C55E", // Delivered/Completed - green
        "#EF4444", // Cancelled - red
    ],
    badgeColors: {
        pending: "bg-yellow-100 text-yellow-700",
        confirmed: "bg-green-100 text-green-700",
        processing: "bg-yellow-100 text-yellow-700",
        shipped: "bg-indigo-100 text-indigo-700",
        delivered: "bg-green-100 text-green-700",
        completed: "bg-green-100 text-green-700",
        cancelled: "bg-red-100 text-red-700",
        refunded: "bg-purple-100 text-purple-700",
    },
};

// Helper functions
function hexToRGBA(hex, alpha = 1) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function formatCurrency(amount) {
    return parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Initialize dashboard
document.addEventListener("DOMContentLoaded", function () {
    console.log("Modern Dashboard initializing...");
    initDashboard();
    setupPeriodButtons();
});

// Setup period buttons with better UX
function setupPeriodButtons() {
    document.querySelectorAll(".period-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".period-btn").forEach((b) => {
                b.classList.remove("active", "bg-blue-500", "text-white");
                b.classList.add("text-gray-700", "hover:bg-gray-100");
            });
            this.classList.add("active", "bg-blue-500", "text-white");
            this.classList.remove("text-gray-700", "hover:bg-gray-100");
            const period = this.textContent.trim().toLowerCase();
            changePeriod(period);
        });
    });
}

// Initialize dashboard components
async function initDashboard() {
    updateCurrentDate();
    await loadDashboardStats();
    await initSalesChart();
    await initTopProductsChart();
    await initOrderStatusChart();
    await loadRecentOrders();
    await loadPerformanceMetrics();
    startAnimations();
}

// Update current date
function updateCurrentDate() {
    const dateElement = document.getElementById("currentDate");
    if (dateElement) {
        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        dateElement.textContent = new Date().toLocaleDateString(
            "en-US",
            options
        );
    }
}

// Animate numbers with smooth counting effect
function animateValue(element, start, end, duration, prefix = "", suffix = "") {
    if (!element) return;

    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const easeProgress = 1 - Math.pow(1 - progress, 3); // Ease out cubic
        const value = Math.floor(easeProgress * (end - start) + start);

        if (prefix.includes("$")) {
            element.textContent = prefix + value.toLocaleString("en-US");
        } else {
            element.textContent = prefix + value.toLocaleString() + suffix;
        }

        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Load dashboard stats with error handling
async function loadDashboardStats() {
    try {
        const response = await fetch("/admin/dashboard-stats");
        if (!response.ok)
            throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();

        // Animate stat cards
        const stats = [
            { id: "revenueCount", value: data.total_revenue || 0, prefix: "$" },
            { id: "ordersCount", value: data.active_orders || 0 },
            { id: "productsCount", value: data.total_products || 0 },
            { id: "customersCount", value: data.total_customers || 0 },
        ];

        stats.forEach((stat, index) => {
            const element = document.getElementById(stat.id);
            if (element) {
                setTimeout(() => {
                    animateValue(
                        element,
                        0,
                        stat.value,
                        1200,
                        stat.prefix || ""
                    );
                }, index * 200);
            }
        });

        // Update growth indicators
        updateGrowthIndicator("revenueGrowth", data.revenue_growth || 0, " vs last month");

        // Update other indicators
        updateIndicator("ordersToday", data.orders_today || 0, " today");
        updateIndicator("lowStock", data.low_stock || 0, " low stock");
        updateIndicator("newCustomers", data.new_customers || 0, " this week");
        updateIndicator("inventoryPercent", data.inventory_percent || 0, "%");
        updateIndicator("pendingShipments", data.pending_shipments || 0, "");

        // Animate inventory bar
        const inventoryBar = document.getElementById("inventoryBar");
        if (inventoryBar && data.inventory_percent) {
            setTimeout(() => {
                inventoryBar.style.width = data.inventory_percent + "%";
            }, 1000);
        }
    } catch (error) {
        console.error("Error loading dashboard stats:", error);
        showErrorMessage("Failed to load dashboard statistics");
    }
}

// Update growth indicator with color coding + suffix support
function updateGrowthIndicator(elementId, value, suffix = "") {
    const element = document.getElementById(elementId);
    if (!element) return;

    const absValue = Math.abs(value);
    const isPositive = value >= 0;

    element.textContent = `${isPositive ? "+" : "-"}${absValue.toFixed(1)}${suffix}`;

    const container = element.closest("span");
    if (container) {
        const icon = container.querySelector("i");

        container.className = `text-xs font-medium px-2 py-1 rounded flex items-center gap-1 ${
            isPositive
                ? "text-green-800 bg-green-100"
                : "text-red-800 bg-red-100"
        }`;

        if (icon) {
            icon.className = `fas ${
                isPositive ? "fa-arrow-up" : "fa-arrow-down"
            }`;
        }
    }
}


// Update indicator text
function updateIndicator(elementId, value, suffix = "") {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = value + suffix;
    }
}

// Show error message
function showErrorMessage(message) {
    console.error(message);
    // You could implement a toast notification here
}

// Initialize Sales Chart with modern flat design
async function initSalesChart() {
    const canvas = document.getElementById("salesChart");
    if (!canvas) return;

    try {
        const response = await fetch(`/admin/sales-chart?period=${currentPeriod}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const chartData = await response.json();
        
        // Check if we got error response
        if (chartData.error) {
            console.error("Server error:", chartData.message);
            throw new Error(chartData.message);
        }

        if (!chartData.labels || !chartData.data) {
            console.error("Invalid chart data received:", chartData);
            createFallbackSalesChart(canvas);
            return;
        }

        const ctx = canvas.getContext("2d");

        // Destroy existing chart
        if (salesChart) {
            salesChart.destroy();
        }

        // Create modern chart
        salesChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: "Revenue",
                        data: chartData.data,
                        borderColor: creativePalette.primary,
                        backgroundColor: hexToRGBA(creativePalette.primary, 0.1),
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 6,
                        pointBackgroundColor: creativePalette.primary,
                        pointBorderColor: "#FFFFFF",
                        pointBorderWidth: 2,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: creativePalette.secondary,
                        pointHoverBorderColor: "#FFFFFF",
                        pointHoverBorderWidth: 3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: creativePalette.dark,
                        titleColor: "#FFFFFF",
                        bodyColor: "#FFFFFF",
                        borderColor: creativePalette.primary,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        titleFont: { size: 13, weight: "500" },
                        bodyFont: { size: 14, weight: "600" },
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return `$${context.parsed.y.toLocaleString("en-US", {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}`;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "#E5E7EB",
                            drawBorder: false,
                        },
                        border: {
                            dash: [4, 4],
                            display: false,
                        },
                        ticks: {
                            callback: function (value) {
                                if (value >= 1000000)
                                    return "$" + (value / 1000000).toFixed(1) + "M";
                                if (value >= 1000)
                                    return "$" + (value / 1000).toFixed(0) + "K";
                                return "$" + value;
                            },
                            color: creativePalette.dark,
                            font: { size: 12, weight: "500" },
                            padding: 8,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        border: { display: false },
                        ticks: {
                            color: creativePalette.dark,
                            font: { size: 12, weight: "500" },
                            padding: 8,
                            maxRotation: 45,
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                animations: {
                    tension: {
                        duration: 1000,
                        easing: "easeOutQuart",
                    },
                },
            },
        });

        // Update sales summary if elements exist
        updateSalesSummary(chartData);

    } catch (error) {
        console.error("Error loading sales chart:", error);
        // Create fallback chart
        createFallbackSalesChart(canvas);
    }
}

// Update sales summary display
function updateSalesSummary(chartData) {
    const totalElem = document.getElementById("salesTotal");
    const avgElem = document.getElementById("salesAverage");
    
    if (totalElem && chartData.total_sales !== undefined) {
        totalElem.textContent = `$${formatCurrency(chartData.total_sales)}`;
    }
    
    if (avgElem && chartData.average_sales !== undefined) {
        avgElem.textContent = `$${formatCurrency(chartData.average_sales)}`;
    }
}

// Fallback chart creation
function createFallbackSalesChart(canvas) {
    const ctx = canvas.getContext("2d");
    
    if (salesChart) {
        salesChart.destroy();
    }

    // Sample data based on current period
    let labels, data;
    if (currentPeriod === 'week') {
        labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        data = [1500, 2300, 1800, 2900, 3200, 2800, 3500];
    } else if (currentPeriod === 'month') {
        labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        data = [12500, 18900, 15600, 23400];
    } else {
        labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        data = [45000, 52000, 49000, 61000, 73000, 68000, 75000, 82000, 78000, 91000, 85000, 95000];
    }

    salesChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Revenue",
                    data: data,
                    borderColor: creativePalette.primary,
                    backgroundColor: hexToRGBA(creativePalette.primary, 0.1),
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 6,
                    pointBackgroundColor: creativePalette.primary,
                    pointBorderColor: "#FFFFFF",
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: creativePalette.secondary,
                    pointHoverBorderColor: "#FFFFFF",
                    pointHoverBorderWidth: 3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: creativePalette.dark,
                    titleColor: "#FFFFFF",
                    bodyColor: "#FFFFFF",
                    borderColor: creativePalette.primary,
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function (context) {
                            return `$${context.parsed.y.toLocaleString("en-US")}`;
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: "#E5E7EB" },
                    ticks: {
                        callback: function (value) {
                            if (value >= 1000000) return "$" + (value / 1000000).toFixed(1) + "M";
                            if (value >= 1000) return "$" + (value / 1000).toFixed(0) + "K";
                            return "$" + value;
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Update summary with fallback data
    const total = data.reduce((a, b) => a + b, 0);
    const average = data.length > 0 ? total / data.length : 0;
    
    updateSalesSummary({
        total_sales: total,
        average_sales: average
    });
}

// Function to change period and update chart
async function changePeriod(period) {
    currentPeriod = period;
    
    // Update active button state
    document.querySelectorAll('.period-btn').forEach(btn => {
        if (btn.textContent.trim().toLowerCase() === period) {
            btn.classList.add('active', 'bg-blue-500', 'text-white');
            btn.classList.remove('text-gray-700', 'hover:bg-gray-100');
        } else {
            btn.classList.remove('active', 'bg-blue-500', 'text-white');
            btn.classList.add('text-gray-700', 'hover:bg-gray-100');
        }
    });
    
    // Update chart with new period
    await initSalesChart();
}

// Initialize Top Products Chart with creative bar design
async function initTopProductsChart() {
    const canvas = document.getElementById("topProductsChart");
    if (!canvas) return;

    try {
        const response = await fetch("/admin/top-products");
        if (!response.ok)
            throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        const products = data.top_products || [];

        if (products.length === 0) {
            drawNoDataMessage(canvas, "No products data available");
            return;
        }

        const ctx = canvas.getContext("2d");
        const labels = products.map((p) => {
            const name = p.name || "Unknown Product";
            return name.length > 15 ? name.substring(0, 15) + "..." : name;
        });
        const salesData = products.map((p) => parseInt(p.total_sold) || 0);

        if (topProductsChart) topProductsChart.destroy();

        topProductsChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Units Sold",
                        data: salesData,
                        backgroundColor: creativePalette.barColors,
                        borderRadius: {
                            topLeft: 8,
                            topRight: 8,
                            bottomLeft: 0,
                            bottomRight: 0,
                        },
                        borderWidth: 0,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: creativePalette.dark,
                        titleColor: "#FFFFFF",
                        bodyColor: "#FFFFFF",
                        padding: 12,
                        displayColors: true,
                        boxPadding: 4,
                        titleFont: { size: 13, weight: "500" },
                        bodyFont: { size: 14, weight: "600" },
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${context.parsed.y}`;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "#E5E7EB",
                            drawBorder: false,
                        },
                        border: { display: false },
                        ticks: {
                            color: creativePalette.dark,
                            font: { size: 12, weight: "500" },
                            padding: 8,
                            stepSize: Math.ceil(Math.max(...salesData) / 5),
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        border: { display: false },
                        ticks: {
                            color: creativePalette.dark,
                            font: { size: 11, weight: "500" },
                            maxRotation: 45,
                            minRotation: 45,
                        },
                    },
                },
                animation: {
                    duration: 1500,
                    easing: "easeOutQuart",
                },
            },
        });
    } catch (error) {
        console.error("Error loading top products chart:", error);
    }
}

// Draw no data message
function drawNoDataMessage(canvas, message) {
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.font = "14px -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto";
    ctx.fillStyle = "#9CA3AF";
    ctx.textAlign = "center";
    ctx.fillText(message, canvas.width / 2, canvas.height / 2);
}

// Initialize Order Status Chart with modern donut design
async function initOrderStatusChart() {
    const canvas = document.getElementById("orderStatusChart");
    if (!canvas) return;

    try {
        const response = await fetch("/admin/order-status-distribution");
        const data = await response.json();
        const ctx = canvas.getContext("2d");

        if (orderStatusChart) orderStatusChart.destroy();

        // Use the status labels from backend or fallback
        const statusLabels = data.order_status_labels || [
            "Pending",
            "Confirmed",
            "Processing",
            "Shipped",
            "Delivered",
            "Cancelled",
            "Refunded"
        ];

        // Use the distribution data from backend
        const distributionData = data.order_status_distribution || [0, 0, 0, 0, 0, 0, 0];

        // Take only first 5 for the donut chart
        const labelsForChart = statusLabels.slice(0, 5);
        const dataForChart = distributionData.slice(0, 5);

        orderStatusChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: labelsForChart,
                datasets: [
                    {
                        data: dataForChart,
                        backgroundColor: creativePalette.donutColors,
                        borderWidth: 3,
                        borderColor: "#FFFFFF",
                        hoverOffset: 15,
                        hoverBorderWidth: 4,
                        spacing: 3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "right",
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: "circle",
                            font: { size: 12, weight: "500" },
                            color: creativePalette.dark,
                        },
                    },
                    tooltip: {
                        backgroundColor: creativePalette.dark,
                        titleColor: "#FFFFFF",
                        bodyColor: "#FFFFFF",
                        padding: 12,
                        displayColors: true,
                        boxPadding: 4,
                        titleFont: { size: 13, weight: "500" },
                        bodyFont: { size: 14, weight: "600" },
                        cornerRadius: 8,
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
                                    : 0;
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
                    easing: "easeOutQuart",
                },
            },
        });
    } catch (error) {
        console.error("Error loading order status chart:", error);
    }
}

// Initialize Revenue Comparison Chart (optional - you can remove if not needed)
async function initRevenueComparisonChart() {
    const canvas = document.getElementById("revenueComparisonChart");
    if (!canvas) return;

    try {
        // This endpoint might not exist, so we'll use fallback
        createFallbackRevenueChart(canvas);
    } catch (error) {
        console.error("Error loading revenue comparison chart:", error);
    }
}

function createFallbackRevenueChart(canvas) {
    const ctx = canvas.getContext("2d");

    if (revenueComparisonChart) revenueComparisonChart.destroy();

    // Sample data for revenue comparison
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const revenueData = [45000, 52000, 49000, 61000, 73000, 68000];
    const ordersData = [45, 52, 49, 61, 73, 68];

    revenueComparisonChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: months,
            datasets: [
                {
                    label: "Revenue",
                    data: revenueData,
                    borderColor: creativePalette.primary,
                    backgroundColor: hexToRGBA(creativePalette.primary, 0.1),
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    yAxisID: "y",
                },
                {
                    label: "Orders",
                    data: ordersData,
                    borderColor: creativePalette.success,
                    backgroundColor: hexToRGBA(creativePalette.success, 0.1),
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    yAxisID: "y1",
                    borderDash: [5, 5],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: "index",
                intersect: false,
            },
            stacked: false,
            plugins: {
                legend: {
                    position: "top",
                    labels: {
                        font: { size: 12, weight: "500" },
                        padding: 20,
                        usePointStyle: true,
                    },
                },
                tooltip: {
                    backgroundColor: creativePalette.dark,
                    titleColor: "#FFFFFF",
                    bodyColor: "#FFFFFF",
                    borderColor: creativePalette.primary,
                    borderWidth: 1,
                    padding: 12,
                    titleFont: { size: 13, weight: "500" },
                    bodyFont: { size: 14, weight: "600" },
                    cornerRadius: 8,
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: creativePalette.dark,
                        font: { size: 12, weight: "500" },
                    },
                },
                y: {
                    type: "linear",
                    display: true,
                    position: "left",
                    grid: {
                        color: "#E5E7EB",
                    },
                    ticks: {
                        callback: function (value) {
                            return "$" + value.toLocaleString();
                        },
                        color: creativePalette.dark,
                        font: { size: 12, weight: "500" },
                    },
                },
                y1: {
                    type: "linear",
                    display: true,
                    position: "right",
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        color: creativePalette.success,
                        font: { size: 12, weight: "500" },
                    },
                },
            },
        },
    });
}

// Load Recent Orders
async function loadRecentOrders() {
    const tableBody = document.getElementById("recentOrdersList");
    if (!tableBody) return;

    try {
        const response = await fetch("/admin/recent-activity");
        if (!response.ok)
            throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        const orders = data.recent_orders || [];

        if (orders.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-12">
                        <div class="flex flex-col items-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p class="text-sm font-medium">No recent orders</p>
                            <p class="text-xs mt-1">Orders from last 60 days will appear here</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

     tableBody.innerHTML = orders
    .slice(0, 3) // only 3 most recent
    .map((order) => `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
            <td class="py-4 px-4">
                <span class="font-mono text-sm font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded">
                    #${order.order_number ?? order.id}
                </span>
            </td>
            <td class="py-4 px-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                    <span class="text-gray-700 font-medium">
                        ${order.user?.name ?? "Guest"}
                    </span>
                </div>
            </td>
            <td class="py-4 px-4">
                <span class="font-bold text-gray-900">
                    $${Number(order.total_amount ?? 0).toLocaleString("en-US")}
                </span>
            </td>
            <td class="py-4 px-4">
                ${getModernStatusBadge(order.order_status)}
            </td>
            <td class="py-4 px-4">
                <span class="text-sm text-gray-600">
                    ${formatOrderDate(order.created_at)}
                </span>
            </td>
        </tr>
    `)
    .join("");


    } catch (error) {
        console.error("Error loading recent orders:", error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-12 text-red-600">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                        <p class="text-sm font-medium">Failed to load orders</p>
                        <button onclick="loadRecentOrders()" class="mt-3 text-xs text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-redo mr-1"></i>Retry
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }
}

function getModernStatusBadge(status) {
    const classes = creativePaletteOfOrderStatus.badgeColors[status] || "bg-gray-100 text-gray-700";

    // Capitalize first letter
    const label = status.charAt(0).toUpperCase() + status.slice(1);

    return `<span class="px-3 py-1 text-xs font-semibold rounded-full ${classes}">${label}</span>`;
}


// Format order date with relative time
function formatOrderDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));

    if (diffDays === 0) {
        if (diffHours === 0) return "Just now";
        if (diffHours === 1) return "1 hour ago";
        return `${diffHours} hours ago`;
    }
    if (diffDays === 1) return "Yesterday";
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;

    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: diffDays < 365 ? undefined : "numeric",
    });
}

// Load Performance Metrics with animations
async function loadPerformanceMetrics() {
    try {
        const response = await fetch("/admin/performance-metrics");
        const metrics = await response.json();

        // Animate metrics with staggered delays
        const metricConfigs = [
            {
                valueId: "avgOrderValue",
                barId: "avgOrderBar",
                value: metrics.avg_order_value || 0,
                prefix: "$",
                maxValue: metrics.max_order_value || 50000,
            },
            {
                valueId: "conversionRate",
                barId: "conversionBar",
                value: metrics.conversion_rate || 0,
                suffix: "%",
                maxValue: 100,
            },
            {
                valueId: "satisfaction",
                barId: "satisfactionBar",
                value: metrics.satisfaction_rate || 0,
                suffix: "%",
                maxValue: 100,
            },
            {
                valueId: "fulfillment",
                barId: "fulfillmentBar",
                value: metrics.fulfillment_rate || 0,
                suffix: "%",
                maxValue: 100,
            },
        ];

        metricConfigs.forEach((config, index) => {
            const valueElement = document.getElementById(config.valueId);
            const barElement = document.getElementById(config.barId);

            if (valueElement) {
                setTimeout(() => {
                    valueElement.textContent =
                        (config.prefix || "") +
                        config.value.toLocaleString() +
                        (config.suffix || "");

                    if (barElement) {
                        const percentage = (config.value / config.maxValue) * 100;
                        barElement.style.width = Math.min(percentage, 100) + "%";
                    }
                }, index * 300);
            }
        });
    } catch (error) {
        console.error("Error loading performance metrics:", error);
    }
}

// Refresh dashboard
async function refreshDashboard() {
    const refreshBtn = event?.target?.closest("button");
    let icon = refreshBtn?.querySelector("i");

    if (
        !icon &&
        document.querySelector('button[onclick*="refreshDashboard"] i')
    ) {
        icon = document.querySelector('button[onclick*="refreshDashboard"] i');
    }

    if (icon) icon.classList.add("fa-spin");

    try {
        await Promise.all([
            loadDashboardStats(),
            initSalesChart(),
            initTopProductsChart(),
            initOrderStatusChart(),
            loadRecentOrders(),
            loadPerformanceMetrics(),
        ]);

        // Show success feedback
        if (refreshBtn) {
            refreshBtn.classList.add("bg-green-50", "text-green-700");
            setTimeout(() => {
                refreshBtn.classList.remove("bg-green-50", "text-green-700");
            }, 1000);
        }
    } catch (error) {
        console.error("Error refreshing dashboard:", error);
    } finally {
        if (icon) icon.classList.remove("fa-spin");
    }
}

// Start animations
function startAnimations() {
    if (typeof AOS !== "undefined") {
        AOS.init({
            duration: 800,
            easing: "ease-out-cubic",
            once: true,
            offset: 50,
            delay: 100,
        });
    }
}

// Auto-refresh every 2 minutes
setInterval(() => {
    if (document.visibilityState === "visible") {
        console.log("Auto-refreshing dashboard...");
        loadDashboardStats();
        loadRecentOrders();
    }
}, 120000);