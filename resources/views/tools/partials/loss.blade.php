<div class="tool-grid">
    <div class="tool-field"><label>Fiber distance (km)</label><input data-input="distance" type="number" min="0" step="0.01" value="2"></div>
    <div class="tool-field"><label>Fiber wavelength/type</label><select data-input="attenuation"><option value="0.35">Single-mode 1310 nm, 0.35 dB/km</option><option value="0.25">Single-mode 1550 nm, 0.25 dB/km</option><option value="3.0">Multimode 850 nm, 3.0 dB/km</option><option value="1.0">Multimode 1300 nm, 1.0 dB/km</option></select></div>
    <div class="tool-field"><label>Connectors</label><input data-input="connectors" type="number" min="0" value="2"></div>
    <div class="tool-field"><label>Connector loss (dB each)</label><input data-input="connectorLoss" type="number" min="0" step="0.01" value="0.3"></div>
    <div class="tool-field"><label>Fusion splices</label><input data-input="splices" type="number" min="0" value="4"></div>
    <div class="tool-field"><label>Splice loss (dB each)</label><input data-input="spliceLoss" type="number" min="0" step="0.01" value="0.1"></div>
    <div class="tool-field"><label>Optic power budget (dB)</label><input data-input="budget" type="number" min="0" step="0.01" value="10"></div>
    <div class="tool-field"><label>Safety margin (dB)</label><input data-input="margin" type="number" min="0" step="0.01" value="3"></div>
</div>
<button class="tool-button mt-3" type="button" data-calculate>Calculate Loss</button>
