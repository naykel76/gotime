import * as FilePond from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import 'filepond/dist/filepond.min.css';

// Register plugins
FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType
);

// Make FilePond available globally for Alpine components
window.FilePond = FilePond;

export default FilePond;