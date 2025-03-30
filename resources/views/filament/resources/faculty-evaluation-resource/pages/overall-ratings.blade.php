<div id="faculty-ratings-chart" style="min-height: 280px;"></div>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing chart...');
            var options = {
                chart: {
                    height: 250,
                    type: 'radialBar',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000,
                        animateGradually: {
                            enabled: true,
                            delay: 100
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 800
                        }
                    },
                    toolbar: {
                        show: false
                    }
                },
                series: [0, 0, 0, 0],
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        offsetX: window.innerWidth < 640 ? 0 : (window.innerWidth <= 810 ? 0 : 20),
                        startAngle: 0,
                        endAngle: 270,
                        animationDuration: 1000,
                        animations: {
                            enabled: true,
                            type: 'gradient',
                            speed: 1000,
                            animateGradually: {
                                enabled: true,
                                delay: 100
                            }
                        },
                        hollow: {
                            margin: 2,
                            size: '30%',
                            background: 'transparent',
                        },
                        track: {
                            background: '#e7e7e7',
                            strokeWidth: '100%',
                            margin: 2,
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                blur: 4,
                                opacity: 0.15
                            }
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: '16px',
                                offsetY: 5,
                                color: '#888'
                            },
                            value: {
                                show: true,
                                fontSize: '12px',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                formatter: function(val) {
                                    return val + '%';
                                }
                            },
                            total: {
                                show: true,
                                label: 'Overall',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                                fontSize: '12px',
                                offsetY: -20,
                                formatter: function() {
                                    return '{{ number_format($record->overall_average, 2) }}';
                                }
                            }
                        }
                    }
                },
                labels: ['Commitment', 'Knowledge', 'Teaching', 'Management'],
                colors: ['#4285F4', '#34A853', '#FBBC05', '#EA4335'],
                legend: {
                    show: true,
                    floating: true,
                    fontSize: window.innerWidth < 640 ? '10px' : '13px',
                    position: 'left',
                    offsetX: window.innerWidth < 640 ? 1 : (window.innerWidth <= 810 ? -25 : 48),
                    offsetY: window.innerWidth < 640 ? 0 : 2,
                    labels: {
                        useSeriesColors: true,
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex] + '%';
                    },
                    itemMargin: {
                        vertical: 1
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#faculty-ratings-chart"), options);
            chart.render().then(() => {
                // Animate to actual values after initial render
                setTimeout(() => {
                    chart.updateSeries([
                        {{ round($record->commitment_average * 20, 1) }},
                        {{ round($record->knowledge_average * 20, 1) }},
                        {{ round($record->teaching_average * 20, 1) }},
                        {{ round($record->management_average * 20, 1) }},
                    ]);
                }, 100);
            });

            // Add resize listener
            function updateLegendPosition() {
                const isMobile = window.innerWidth < 640;
                const isTablet = window.innerWidth <= 810;
                console.log('Screen width:', window.innerWidth);
                console.log('Is mobile:', isMobile);
                console.log('Is tablet:', isTablet);
                console.log('Current offset:', options.legend.offsetX);
                options.legend.offsetX = isMobile ? 1 : (isTablet ? -25 : 45);
                console.log('New offset:', options.legend.offsetX);
                options.legend.offsetY = isMobile ? 0 : 2;
                options.legend.fontSize = isMobile ? '11px' : '13px';
                
                chart.updateOptions({
                    legend: {
                        offsetX: isMobile ? 1 : (isTablet ? -25 : 45),
                        offsetY: isMobile ? 0 : 2,
                        fontSize: isMobile ? '11px' : '13px'
                    },
                    plotOptions: {
                        radialBar: {
                            offsetX: isMobile ? 0 : (isTablet ? 0 : 20),
                            // offsetY: isMobile ? 0 : 4,
                        }
                    }
                }, true, true);

                // Reset and animate series
                chart.updateSeries([0, 0, 0, 0], false);
                setTimeout(() => {
                    chart.updateSeries([
                        {{ round($record->commitment_average * 20, 1) }},
                        {{ round($record->knowledge_average * 20, 1) }},
                        {{ round($record->teaching_average * 20, 1) }},
                        {{ round($record->management_average * 20, 1) }},
                    ]);
                }, 100);
            }

            window.addEventListener('resize', updateLegendPosition);

            // Theme change observer
            const darkModeObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            plotOptions: {
                                radialBar: {
                                    dataLabels: {
                                        total: {
                                            color: isDark ? '#ffffff' : '#000000'
                                        },
                                        value: {
                                            color: isDark ? '#ffffff' : '#000000'
                                        }
                                    }
                                }
                            }
                        }, false, true);
                    }
                });
            });

            darkModeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            // Clean up listeners when chart is destroyed
            document.addEventListener('turbo:before-render', function() {
                window.removeEventListener('resize', updateLegendPosition);
                darkModeObserver.disconnect();
            });
        });
    </script>
@endPushOnce
