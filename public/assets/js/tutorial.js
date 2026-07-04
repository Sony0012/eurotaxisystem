/* public/assets/js/tutorial.js */
const TutorialManager = (function () {
    const steps = [
        {
            element: 'a[href*="dashboard"]',
            popover: {
                title: 'Welcome to your Dashboard',
                description: 'Here you can see an overview of your business metrics and system status. Click it to continue.',
                position: 'right'
            },
            requireClick: true
        },
        {
            element: 'a[href*="units"]',
            popover: {
                title: 'Units Management',
                description: 'Click here to manage your fleet, add new cars, and monitor their status.',
                position: 'right'
            },
            requireClick: true
        },
        {
            element: 'a[href*="staff"]',
            popover: {
                title: 'Staff Management',
                description: 'Manage your drivers and admins from this section.',
                position: 'right'
            },
            requireClick: true
        },
        {
            element: 'a[href*="my-account"]',
            popover: {
                title: 'Your Account',
                description: 'Click here to manage your profile and account settings.',
                position: 'right'
            },
            requireClick: true
        }
    ];

    let driverObj = null;

    function init(tutorialCompleted) {
        if (!window.driver) {
            console.error('Driver.js is not loaded.');
            return;
        }

        if (tutorialCompleted && !localStorage.getItem('tutorial_force_restart')) {
            return;
        }

        const currentStepIndex = parseInt(localStorage.getItem('tutorial_current_step') || '0', 10);
        
        if (currentStepIndex === 0 && !localStorage.getItem('tutorial_welcome_shown')) {
            showWelcomeModal();
        } else {
            startTutorial(currentStepIndex);
        }
    }

    function showWelcomeModal() {
        if(document.getElementById('tutorial-welcome-modal')) return;
        const modalHtml = `
            <div id="tutorial-welcome-modal" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 999999; background: rgba(17, 24, 39, 0.8); backdrop-filter: blur(8px);">
                <div id="tutorial-welcome-content" class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center relative border border-gray-100" style="animation: modal-pop-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome!</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">This quick tour will show you how to navigate and use the system effectively.</p>
                    <div class="flex flex-col gap-3">
                        <button id="tutorial-start-btn" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all">Start Tour</button>
                        <button id="tutorial-skip-btn" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">Skip for now</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        document.getElementById('tutorial-start-btn').addEventListener('click', () => {
            document.getElementById('tutorial-welcome-modal').remove();
            localStorage.setItem('tutorial_welcome_shown', '1');
            startTutorial(0);
        });

        document.getElementById('tutorial-skip-btn').addEventListener('click', () => {
            document.getElementById('tutorial-welcome-modal').remove();
            markTutorialComplete();
        });
    }

    function startTutorial(stepIndex) {
        if (stepIndex >= steps.length) {
            finishTutorial();
            return;
        }

        const step = steps[stepIndex];
        const targetElement = document.querySelector(step.element);
        
        if (!targetElement) {
            console.warn('Tutorial target element not found: ', step.element);
            // If element is not on page, they might have navigated somewhere else.
            // We just cancel the tutorial for now.
            return;
        }

        const isLastStep = stepIndex === steps.length - 1;

        driverObj = window.driver.driver({
            showProgress: true,
            progressText: \`Step \${stepIndex + 1} of \${steps.length}\`,
            allowClose: false,
            overlayColor: 'rgba(17, 24, 39, 0.85)',
            animate: true,
            smoothScroll: true,
            keyboardControl: true,
            steps: [
                {
                    element: step.element,
                    popover: {
                        title: step.popover.title,
                        description: step.popover.description,
                        position: step.popover.position || 'auto',
                        showButtons: step.requireClick ? ['close'] : ['next', 'close'],
                        doneBtnText: isLastStep ? 'Finish' : 'Done',
                        closeBtnText: 'Skip Tutorial',
                        onNextClick: () => {
                            if (!step.requireClick) moveToNextStep(stepIndex);
                        }
                    }
                }
            ],
            onPopoverRender: (popover, { config, state }) => {
                if (step.requireClick) {
                    popover.wrapper.classList.add('tutorial-force-click');
                }
            },
            onDestroyStarted: () => {
                if(driverObj) driverObj.destroy();
                markTutorialComplete();
            }
        });

        const clickHandler = (e) => {
            // Only advance if the clicked element is actually the target
            if (targetElement.contains(e.target) || e.target === targetElement) {
                setTimeout(() => {
                    moveToNextStep(stepIndex);
                }, 10);
                targetElement.removeEventListener('click', clickHandler);
            }
        };
        targetElement.addEventListener('click', clickHandler);

        driverObj.drive();
    }

    function moveToNextStep(currentIndex) {
        const nextIndex = currentIndex + 1;
        localStorage.setItem('tutorial_current_step', nextIndex);
        if (nextIndex >= steps.length) {
            finishTutorial();
        } else {
            // Wait to see if page unloads due to navigation.
            // If it doesn't navigate within 600ms, force next step manually.
            setTimeout(() => {
                if (driverObj) driverObj.destroy();
                startTutorial(nextIndex);
            }, 600);
        }
    }

    function finishTutorial() {
        if (driverObj) driverObj.destroy();
        markTutorialComplete();
    }

    function markTutorialComplete() {
        localStorage.removeItem('tutorial_current_step');
        localStorage.removeItem('tutorial_welcome_shown');
        localStorage.removeItem('tutorial_force_restart');
        
        fetch('/api/tutorial/complete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).catch(err => console.error(err));
    }

    function restart() {
        localStorage.setItem('tutorial_force_restart', '1');
        localStorage.setItem('tutorial_current_step', '0');
        localStorage.removeItem('tutorial_welcome_shown');
        window.location.href = '/'; 
    }

    return {
        init,
        restart
    };
})();
window.TutorialManager = TutorialManager;
