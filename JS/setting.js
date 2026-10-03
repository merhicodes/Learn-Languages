// document.addEventListener('DOMContentLoaded', function() {
    const settingsIcon = document.querySelector('.settings-icon');
    const settingsPanel = document.querySelector('.settings-panel');

    // Show the panel on hover
    settingsIcon.addEventListener('mouseenter', function() {
        // alert('hover');
        settingsPanel.style.display = 'block';
    });

    // Keep the panel visible if hovering over the panel itself
    settingsPanel.addEventListener('mouseenter', function() {
        
        settingsPanel.style.display = 'block';
    });

    // Hide the panel when mouse leaves the icon or panel
    settingsIcon.addEventListener('mouseleave', function() {
        // Use a small timeout to prevent flicker
        setTimeout(function() {
            if (!settingsPanel.matches(':hover')) {
                settingsPanel.style.display = 'none';
            }
        }, 100);
    });

    settingsPanel.addEventListener('mouseleave', function() {
        settingsPanel.style.display = 'none';
    });
// });
