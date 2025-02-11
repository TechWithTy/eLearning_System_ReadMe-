import axios from 'axios';
import { auth } from './auth.helpers';
const remoteUrl = 'http://54.165.111.250:8000/api/';
const baseUrl = 'http://127.0.0.1:8000/api/';

async function getAllUsers() {
  try {
    const { token } = localStorage.getItem('user');
    const res = await axios.get(`${remoteUrl}`);
  } catch (error) {}
}

async function editCourse(
  id,
  { title, description, teacher_id, meet_link, enrollment_limit }
) {
  try {
    const res = await axios.put(
      `${remoteUrl}admin/modifyCourse/${id}`,
      {
        title,
        description,
        teacher_id,
        meet_link,
        enrollment_limit,
      },
      auth()
    );
    console.log(res);
    if (res.status === 200) {
      const data = res.data;
      return { data };
    }
  } catch (error) {
    console.log(error);
    const {
      response: {
        data: { message, errors },
      },
    } = error;

    if (errors) {
      const errorMessages = Object.keys(errors).map((key) => {
        const firstError = errors[key][0];
        if (firstError) {
          return firstError;
        }
      });
      return { errorMessages };
    }
    return { message };
  }
}
async function addCourse({
  title,
  description,
  teacher_id,
  meet_link,
  enrollment_limit,
}) {
  try {
    const res = await axios.post(
      `${remoteUrl}admin/addCourse`,
      {
        title,
        description,
        teacher_id,
        enrollment_limit,
      },
      auth()
    );
    console.log(res);
    if (res.status === 200) {
      const data = res.data;
      return { data };
    }
  } catch (error) {
    console.log(error);
    const {
      response: {
        data: { message, errors },
      },
    } = error;

    if (errors) {
      const errorMessages = Object.keys(errors).map((key) => {
        const firstError = errors[key][0];
        if (firstError) {
          return firstError;
        }
      });
      return { errorMessages };
    }
    return { message };
  }
}

async function deleteCourse(id) {
  try {
    await axios.delete(`${remoteUrl}admin/deleteCourse/${id}`, auth());
  } catch (error) {
    console.log(error);
  }
}
export { editCourse, deleteCourse, addCourse };
