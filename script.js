document.addEventListener('DOMContentLoaded', () => {
    const generateButton = document.getElementById('genereer');
    const gridContainer = document.getElementById('grid');
    const colorPicker = document.getElementById('colorPicker');
    const colorBlock = document.getElementById('color-block');
    const errorMessage = document.getElementById('error-message');
    let selectedColor = colorPicker.value;
    let isMouseDown = false;
  
    // Open color picker when color block is clicked
    colorBlock.addEventListener('click', () => {
      colorPicker.click();
    });
  
    // Update color block and selected color when a color is picked
    colorPicker.addEventListener('input', (event) => {
      selectedColor = event.target.value;
      colorBlock.style.backgroundColor = selectedColor;
    });
  
    // Track mouse down/up state
    document.addEventListener('mousedown', () => {
      isMouseDown = true;
    });
  
    document.addEventListener('mouseup', () => {
      isMouseDown = false;
      saveGridToLocalStorage(); // Save grid state when mouse interaction stops
    });
  
    // Generate grid on button click
    generateButton.addEventListener('click', () => {
      const raster = parseInt(document.getElementById('raster').value);
      const grootte = parseInt(document.getElementById('grootte').value);
  
      // Cap raster size
      if (raster > 23) {
        errorMessage.style.display = 'block';
        return;
      } else {
        errorMessage.style.display = 'none';
      }
  
      // Generate the grid and attempt to load saved data
      generateGrid(raster, grootte);
      loadGridFromLocalStorage();
    });
  
    // Generate grid
    function generateGrid(raster, grootte) {
      const pixelSize = grootte * 0.6; // Convert to cell size in pixels
      gridContainer.innerHTML = ''; // Clear previous grid
  
      for (let i = 0; i < raster; i++) {
        const row = document.createElement('tr');
        for (let j = 0; j < raster; j++) {
          const cell = document.createElement('td');
          cell.style.width = `${pixelSize}px`;
          cell.style.height = `${pixelSize}px`;
  
          // Add mouse events for coloring cells
          cell.addEventListener('mousedown', () => {
            cell.style.backgroundColor = selectedColor;
          });
  
          cell.addEventListener('mousemove', () => {
            if (isMouseDown) {
              cell.style.backgroundColor = selectedColor;
            }
          });
  
          row.appendChild(cell);
        }
        gridContainer.appendChild(row);
      }
    }
  
    // Save the current grid to local storage
    function saveGridToLocalStorage() {
      const gridData = [];
      const rows = gridContainer.querySelectorAll('tr');
  
      rows.forEach((row) => {
        const rowData = [];
        row.querySelectorAll('td').forEach((cell) => {
          rowData.push(cell.style.backgroundColor || 'white');
        });
        gridData.push(rowData);
      });
  
      localStorage.setItem('pixelGrid', JSON.stringify(gridData));
    }
  
    // Load the grid from local storage
    function loadGridFromLocalStorage() {
      const gridData = JSON.parse(localStorage.getItem('pixelGrid'));
  
      if (gridData) {
        const rows = gridContainer.querySelectorAll('tr');
  
        gridData.forEach((rowData, i) => {
          rowData.forEach((color, j) => {
            const cell = rows[i]?.children[j];
            if (cell) {
              cell.style.backgroundColor = color;
            }
          });
        });
      }
    }
  
    // On page load, generate grid if data exists in local storage
    if (localStorage.getItem('pixelGrid')) {
      const initialGridSize = 10; // Default grid size to match UI
      generateGrid(initialGridSize, 5); // Default size values
      loadGridFromLocalStorage();
    }
  });
  