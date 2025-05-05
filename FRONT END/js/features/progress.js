class ProgressTracker {
    constructor() {
        this.progressData = {
            completedCourses: 0,
            averageScore: 0,
            timeSpent: 0,
            courses: [],
            achievements: []
        };
    }

    async init() {
        try {
            await this.loadProgressData();
        } catch (error) {
            console.warn('Using default progress data due to API unavailability');
        }
        this.updateProgressStats();
        this.renderProgressChart();
        this.renderCourseProgress();
        this.renderAchievements();
    }

    async loadProgressData() {
        try {
            const response = await fetch('/api/progress');
            if (!response.ok) throw new Error('Failed to load progress data');
            this.progressData = await response.json();
        } catch (error) {
            console.error('Error loading progress data:', error);
            throw error; // Re-throw to be caught by init()
        }
    }

    updateProgressStats() {
        const completedCourses = document.getElementById('completed-courses');
        const averageScore = document.getElementById('average-score');
        const timeSpent = document.getElementById('time-spent');

        if (completedCourses) completedCourses.textContent = this.progressData.completedCourses;
        if (averageScore) averageScore.textContent = `${this.progressData.averageScore}%`;
        if (timeSpent) timeSpent.textContent = this.formatTimeSpent(this.progressData.timeSpent);
    }

    formatTimeSpent(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours}h ${mins}m`;
    }

    renderProgressChart() {
        const canvas = document.getElementById('progressChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const chartData = this.prepareChartData();

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Learning Progress',
                    data: chartData.data,
                    borderColor: '#4CAF50',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(76, 175, 80, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    prepareChartData() {
        // This would typically come from the backend
        // For now, we'll use sample data
        return {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [20, 35, 50, 65, 80, 90]
        };
    }

    renderCourseProgress() {
        const container = document.querySelector('.course-progress-list');
        if (!container) return;

        const coursesHTML = this.progressData.courses.map(course => `
            <div class="course-progress">
                <div class="course-info">
                    <h4>${this.escapeHtml(course.title)}</h4>
                    <p>${this.escapeHtml(course.description)}</p>
                </div>
                <div class="progress-bar">
                    <div class="progress" style="width: ${course.progress}%"></div>
                </div>
                <span class="progress-percentage">${course.progress}%</span>
            </div>
        `).join('');

        container.innerHTML = `<h3>Course Progress</h3>${coursesHTML}`;
    }

    renderAchievements() {
        const container = document.querySelector('.achievements-grid');
        if (!container) return;

        const achievementsHTML = this.progressData.achievements.map(achievement => `
            <div class="achievement-badge ${achievement.unlocked ? 'unlocked' : 'locked'}">
                <div class="badge-icon">
                    <i class="fas fa-${this.escapeHtml(achievement.icon)}"></i>
                </div>
                <div class="badge-info">
                    <h4>${this.escapeHtml(achievement.title)}</h4>
                    <p>${this.escapeHtml(achievement.description)}</p>
                </div>
            </div>
        `).join('');

        container.innerHTML = achievementsHTML;
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// Initialize progress tracker
document.addEventListener('DOMContentLoaded', () => {
    const progressTracker = new ProgressTracker();
    progressTracker.init();
}); 