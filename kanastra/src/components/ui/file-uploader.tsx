import React, { useState } from 'react';
import axios from 'axios';
//import { getFiles } from '@/contexts/contextFile';
import Modal from './modal';
import { refreshFiles } from '@/contexts/contextFile';

const FileUploader = () => {
  const [file, setFile] = useState<File | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalMessage, setModalMessage] = useState('');

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const selectedFile = event.target.files ? event.target.files[0] : null;
    setFile(selectedFile);
  };

  const handleDragOver = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault();
    event.currentTarget.style.backgroundColor = '#f0f0f0';
  };

  const handleDragLeave = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault();
    event.currentTarget.style.backgroundColor = '#ffffff';
  };

  const handleDrop = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    if (files && files.length > 0) {
      const selectedFile = files[0];
      setFile(selectedFile);
      event.currentTarget.style.backgroundColor = '#ffffff';
    }
  };

  const handleUpload = async () => {
    if (!file) {
      setModalMessage('Selecione um arquivo');
      setIsModalOpen(true);
      return;
    }

    let formData = new FormData();
    formData.append('file', file);

    const URL = 'http://localhost:8000/api/invoices/upload';

    try {
      const response = await axios(URL, {
               method: 'POST',
               data: formData,
               headers: {
                 'Content-Type': 'multipart/form-data'
               },
             });
      // setIsModalOpen(true);
      // setModalMessage(`Arquivo enviado com sucesso.`);
    } catch (error) {
      // setIsModalOpen(true);
      //setModalMessage('Erro ao enviar arquivo.');
    }
    setFile(null);
    //refreshFiles();
    window.location.reload();
  };

  const fileSizeInMB = file ? (file.size / 1024 / 1024).toFixed(2) : '0';

  return (
    <div
      className="flex flex-col items-center gap-6"
      onDragOver={handleDragOver}
      onDragLeave={handleDragLeave}
      onDrop={handleDrop}
    >
      <label htmlFor="file" className="block text-center bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg cursor-pointer hover:bg-gray-700">
        Selecione o arquivo
        <input id="file" type="file" accept=".csv" onChange={handleFileChange} className="sr-only" />
      </label>

      {file && (
        <div className="mt-4 p-4 w-full max-w-md text-center rounded-lg shadow-lg bg-white">
          <div><strong>Nome:</strong> {file.name}</div>
          <div><strong>Tamanho:</strong> {fileSizeInMB} MB</div>
          <button
            onClick={handleUpload}
            className="mt-4 px-6 py-3 rounded-lg font-semibold text-lg bg-gray-600 text-white hover:bg-gray-700"
          >
            Upload
          </button>
        </div>
      )}

      <Modal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} title="Status do envio do arquivo">
        <div className="text-center">
          {modalMessage}
        </div>
      </Modal>
    </div>
  );
};

export { FileUploader };
